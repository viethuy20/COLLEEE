<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Content;
use App\CreditCard;
use App\Affiriate;
use App\Search\CreditCardCondition;
use App\Services\Meta;

class CreditCardsController extends Controller
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * 検索実行.
     * @param ProgramCondition $condition 条件
     * @return LengthAwarePaginator {@link LengthAwarePaginator}
     */
    private function getPaginator(CreditCardCondition $condition)
    {
        $now = Carbon::now();
        $builder = CreditCard::ofEnable()
                ->join('programs', function ($join) use($now) {
                    $join->on('credit_cards.program_id', '=', 'programs.id')
                        ->where('programs.status', '=', 0)
                        ->where('programs.stop_at', '>=', $now)
                        ->where('programs.start_at', '<=', $now);
                })
                ->join('points', function ($join) use($now) {
                    $join->on('credit_cards.program_id', '=', 'points.program_id')
                        ->where('points.status', '=', 0)
                        ->where('points.stop_at', '>=', $now)
                        ->where('points.start_at', '<=', $now);
                })
                ->join('affiriates', function ($join) use($now) {
                    $join->on('credit_cards.program_id', '=', 'affiriates.parent_id')
                        ->where('affiriates.parent_type', '=', Affiriate::PROGRAM_TYPE)
                        ->where('affiriates.status', '=', 0)
                        ->where('affiriates.stop_at', '>=', $now)
                        ->where('affiriates.start_at', '<=', $now);
                })
                ->select('credit_cards.*');

        // ブランド検索
        $brands = $condition->getParam('brands');
        $brand_ids = $condition->getIdList($brands);
        if (isset($brand_ids)) {
            $builder = $builder->ofBrand($brand_ids);
        }
        // 電子マネー検索
        $emoneys = $condition->getParam('emoneys');
        $emoney_ids = $condition->getIdList($emoneys);
        if (isset($emoney_ids)) {
            $builder = $builder->ofEmoney($emoney_ids);
        }
        // 付帯保険検索
        $insurances = $condition->getParam('insurances');
        $insurance_ids = $condition->getIdList($insurances);
        if (isset($insurance_ids)) {
            $builder = $builder->ofInsurance($insurance_ids);
        }
        // 年会費検索
        $annual_free = $condition->getParam('annual_free');
        if (isset($annual_free) && $annual_free) {
            $builder = $builder->where('credit_cards.annual_free', '=', 1);
        }
        // ETC検索
        $etc = $condition->getParam('etc');
        if (isset($etc) && $etc) {
            $builder = $builder->where('credit_cards.etc', '=', 1);
        }
        // ApplePay検索
        $apple_pay = $condition->getParam('apple_pay');
        if (isset($apple_pay) && $apple_pay) {
            $builder = $builder->where('credit_cards.apple_pay', '=', 1);
        }

        // 総件数取得
        $total = $builder->count();
        
        // 件数
        $limit = $condition->getParam('limit');

        // ページ数
        $page = min(max($condition->getParam('page'), 1), ceil($total / $limit));

        // ソート
        $sort = $condition->getParam('sort');
        switch ($sort) {
            case 1:
                // ポイント獲得順
                $builder = $builder->orderBy('points.point', 'desc');
                break;
            case 2:
                // 獲得時期順
                $builder = $builder->orderBy('affiriates.accept_days', 'asc');
                break;
            case 3:
                // 還元率順
                $builder = $builder->orderBy('credit_cards.back', 'desc');
                break;
            default:
                // 獲得ポイント順
                $sort = 1;
                $builder = $builder->orderBy('points.point', 'desc');
                break;
        }
        $condition->setParams(['sort' => $sort, 'page' => $page]);

        $builder = $builder->take($limit)
                ->skip(($page - 1) * $limit);

        // プログラムリスト取得
        $program_list = $builder->get();
        // ページネーション作成
        $paginator = new LengthAwarePaginator($program_list, $total, $limit, $page);

        return $paginator;
    }

    /**
     * クレジットカード検索.
     * @param Request $request {@link Request}
     * @param int $sort ソート
     * @param int $page ページ
     */
    public function search(Request $request, $sort = 0, $page = 1) {
        // 検索条件
        $condition = new CreditCardCondition();
        $condition->setParams(['sort' => $sort, 'page' => $page]);

        // ブランド検索
        if ($request->has('brand')) {
            $condition->setParams(['brands' => array_sum($request->input('brand'))]);
        } else if ($request->has('brands')) {
            $condition->setParams(['brands' => $request->input('brands')]);
        }
        // 電子マネー検索
        if ($request->has('emoney')) {
            $condition->setParams(['emoneys' => array_sum($request->input('emoney'))]);
        } else if ($request->has('emoneys')) {
            $condition->setParams(['emoneys' => $request->input('emoneys')]);
        }
        // 付帯保険検索
        if ($request->has('insurance')) {
            $condition->setParams(['insurances' => array_sum($request->input('insurance'))]);
        } else if ($request->has('insurances')) {
            $condition->setParams(['insurances' => $request->input('insurances')]);
        }
        // 年会費検索
        if ($request->has('annual_free') && $request->input('annual_free') == 1) {
            $condition->setParams(['annual_free' => true]);
        }
        // ETC検索
        if ($request->has('etc') && $request->input('etc') == 1) {
            $condition->setParams(['etc' => true]);
        }
        // ApplePay検索
        if ($request->has('apple_pay') && $request->input('apple_pay') == 1) {
            $condition->setParams(['apple_pay' => true]);
        }

        // 検索実行
        $paginator = $this->getPaginator($condition);

        // breadcrumb for credit cards page.
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(200);
        if (!\Agent::isPhone()) {
            $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        }
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '" },';
            $position++;
        }
        $link = route('credit_cards.list');
        $text = '対象クレジットカード一覧';
        if (!\Agent::isPhone()) {
            $text = 'クレジットカード徹底比較';
        }
        $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "' . $text . '", "item": "' . $link . '" }';

        return view('credit_cards.list',  [
            'paginator' => $paginator, 
            'condition' => $condition,
            'arr_breadcrumbs' => $arr_breadcrumbs,
            'application_json' => $application_json
        ]);
    }
}
