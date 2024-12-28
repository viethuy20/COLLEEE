<?php
namespace App\Search;

use Carbon\Carbon;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Affiriate;
use App\Point;
use App\Program;
use App\ProgramLabel;

/**
 * プログラム検索条件.
 */
class ProgramCondition
{
    /**
     *パラメーター.
     */
    protected $params = [];

    protected $key_list;

    public function __construct()
    {
        //parent::__construct();
        $this->key_list = ['page', 'sort', 'limit', 'll', 'shop_category_id',
            'keyword_list', 'all_back', 'content_spot_id'];
        $this->params['page'] = 1;
        $this->params['sort'] = 0;
        $this->params['limit'] = 10;
    }

    public function setParams(array $params)
    {
        foreach ($params as $key => $param) {
            if (in_array($key, $this->key_list)) {
                $this->params[$key] = $param;
            }
        }
    }

    public function getParam(string $key)
    {
        if (!in_array($key, $this->key_list) || !isset($this->params[$key])) {
            return null;
        }
        return $this->params[$key];
    }

    /**
     * リストURL取得.
     * @param object|NULL $ext 更新
     * @return string URL
     */
    public function getListUrl($ext = null) : string
    {
        return self::getStaticListUrl($ext, $this);
    }

    /**
     * リストURL取得.
     * @param object|NULL $ext 更新
     * @param ProgramCondition|NULL $default デフォルト値
     * @return string URL
     */
    public static function getStaticListUrl($ext = null, $default = null) : string
    {
        /** 空の場合. */
        if (!isset($default)) {
            $default = new self();
        }
        $route_list = [];
        // ページ
        $page = $ext->page ?? $default->getParam('page');
        if ($page != 1) {
            $route_list['page'] = $page;
        }
        // 並び順
        $sort = $ext->sort ?? $default->getParam('sort');
        if (!empty($route_list) || $sort != 0) {
            $route_list['sort'] = $sort;
        }
        $url = route('programs.list', $route_list);

        $get_params = [];
        // キーワード検索
        $keyword_list = $ext->keyword_list ?? $default->getParam('keyword_list');
        if (isset($keyword_list)) {
            $get_params['keywords'] = implode(' ', $keyword_list);
        }
        // ショップカテゴリ検索
        $shop_category_id = $ext->shop_category_id ?? $default->getParam('shop_category_id');
        if (isset($shop_category_id)) {
            $get_params['shop_category_id'] = $shop_category_id;
        }
        // 100%還元ポイント検索
        $all_back = $ext->all_back ?? $default->getParam('all_back');
        if (isset($all_back) && $all_back) {
            $get_params['all_back'] = 1;
        }
        // ラベル検索
        $ll = $ext->ll ?? $default->getParam('ll');
        if (isset($ll)) {
            $get_params['ll'] = $ll;
        }
        // コンテンツ検索
        $content_spot_id = $ext->content_spot_id ?? $default->getParam('content_spot_id');
        if (isset($content_spot_id)) {
            $get_params['content_spot_id'] = $content_spot_id;
        }

        return empty($get_params) ? $url : $url.'?'.(http_build_query($get_params));
    }

    public function getPaginator()
    {
        $now = Carbon::now();

        $point_sub_query = Point::ofEnable()
            ->selectRaw(
                    'program_id
                    , sum(point) as point
                    , sum(rate) as rate
                    , max(all_back) as all_back
                    , max(time_sale) as time_sale
                    , max(today_only) as today_only
                    , min(fee_type) as fee_type
                    , max(bonus) as bonus
                    , min(start_at) as start_at
                    , max(stop_at) as stop_at
                    , min(sale_stop_at) as sale_stop_at
                    , min(created_at) as created_at
                    , max(updated_at) as updated_at')
            ->groupBy('program_id');

        $builder = Program::ofList()
            ->joinSub($point_sub_query, 'points', function ($join) {
                $join->on('programs.id', '=', 'points.program_id');
            })
            ->join('affiriates', function ($join) use ($now) {
                $join->on('programs.id', '=', 'affiriates.parent_id')
                    ->where('affiriates.parent_type', '=', Affiriate::PROGRAM_TYPE)
                    ->where('affiriates.status', '=', 0)
                    ->where('affiriates.stop_at', '>=', $now)
                    ->where('affiriates.start_at', '<=', $now);
            });
        // キーワード検索
        $keyword_list = $this->getParam('keyword_list');
        if (isset($keyword_list)) {
            $builder = $builder->ofKeyword($keyword_list);
        }
        // ショップカテゴリ検索
        $shop_category_id = $this->getParam('shop_category_id');
        if (isset($shop_category_id)) {
            $builder = $builder->ofShopCategory($shop_category_id);
        }
        // 100%還元ポイント
        $all_back = $this->getParam('all_back');
        if (isset($all_back) && $all_back) {
            $builder = $builder->where('points.all_back', '=', 1);
        }
        // ラベル検索
        $label_id_list = $this->getParam('ll');
        if (isset($label_id_list)) {
            $builder = $builder->ofLabel($label_id_list);
        }
        // コンテンツ検索
        $content_spot_id = $this->getParam('content_spot_id');
        if (isset($content_spot_id)) {
            $builder = $builder->ofContentSpot($content_spot_id);
        }

        // 総件数取得
        $total = $builder->count();

        // 件数
        $limit = $this->getParam('limit');
        // ソート
        $sort = $this->getParam('sort');

        // ページ数
        $page = min(max($this->getParam('page'), 1), ceil($total / $limit));
        $this->setParams(['sort' => $sort, 'page' => $page]);

        // プログラムリスト取得
        $program_list = $builder->select('programs.*')
            ->ofSort($sort)
            ->take($limit)
            ->skip(($page - 1) * $limit)
            ->get();
        // ページネーション作成
        return new LengthAwarePaginator($program_list, $total, $limit, $page);
    }
}
