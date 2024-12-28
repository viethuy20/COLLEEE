<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Affiriate;
use App\CreditCard;
use App\Asp;
use App\Device\Device;
use App\ExternalLink;
use App\Http\Middleware\Lock;
use App\Label;
use App\Program;
use App\ReviewPointManagement;
use App\Search\ProgramCondition;
use App\Tag;
use App\User;
use App\Services\Meta;
use App\External\Logrecoai;
use App\External\History;

class ProgramsController extends Controller
{

    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * キーワード検索.
     * @param Request $request {@link Request}
     * @param int $sort ソート
     * @param int $page ページ
     */
    public function search(Request $request, $sort = 0, $page = 1)
    {
        $ll = $request->input('ll');
        // 検索条件
        $condition = new ProgramCondition();
        $condition->setParams(['sort' => $sort, 'page' => $page]);

        $keywords = $request->input('keywords') ?? '';
        // キーワード検索
        if ($request->has('keywords')) {
            // キーワード取得
            $keywords = trim(str_replace('　', ' ', $keywords));
            $tmp_list = explode(' ', $keywords);
            $keyword_list = [];
            foreach ($tmp_list as $keyword) {
                if (mb_strlen($keyword) < 1) {
                    continue;
                }
                $keyword_list[] = $keyword;
            }
            // キーワード検索条件登録追加
            $condition->setParams(['keyword_list' => $keyword_list]);
        }
        // ショップカテゴリ検索
        if ($request->has('shop_category_id')) {
            // ショップカテゴリ検索条件追加
            $condition->setParams(['shop_category_id' => $request->input('shop_category_id')]);
        }
        // 100%還元ポイント検索
        if ($request->has('all_back') && $request->input('all_back') == 1) {
            // 100%還元ポイント検索条件追加
            $condition->setParams(['all_back' => true]);
        }
        // ラベル検索
        $label_name = '';
        if ($request->has('ll')) {
            // ラベル取得
            $labels = $request->input('ll') ?? '';
            // 配列か検証
            if (is_array($labels)) {
                //ラベルが存在するか検証
                $label_list = Label::whereIn('id', $labels)->pluck('id')->all();
                if (!empty($label_list)) {
                    $condition->setParams(['ll' => $label_list]);
                }
                //ラベル名を取得する
                $label_name = Label::whereIn('id', $labels)->pluck('name')->first();
            }
        }
        // コンテンツ検索
        if ($request->has('content_spot_id')) {
            $condition->setParams(['content_spot_id' => $request->input('content_spot_id')]);
        }

        // 検索実行
        $paginator = $condition->getPaginator();
        $user_program_id_list = [];
        // ログインしている場合、お気に入り登録プログラムID一覧を取得
        if (Auth::check() && $paginator->count() > 0) {
            $program_id_list = [];
            $program_list = $paginator->items();
            foreach ($program_list as $program) {
                $program_id_list[] = $program->id;
            }
            $user_program_id_list = Auth::user()->fav_programs()
                ->wherePivotIn('program_id', $program_id_list)
                ->pluck('programs.id')
                ->all();
        }

        //meta description
        $meta_description = "GMOポイ活から参加できる広告が一覧で表示されます。 | 貯めたポイントは現金やギフト券に交換することができます。";
        $arr_breadcrumbs = [];
        $application_json = '';
        $page_num = '';
        $page = '';
        if ($paginator->total() > 0){
            $page = $paginator->currentPage();
            $page_num = '（'.$paginator->currentPage().'ページ目）';
        }
        $route_list = [];
        // ページ
        if ($page > 1) {
            $route_list['page'] = $page;
        }
        // 並び順
        if (!empty($route_list) || $sort != 0) {
            $route_list['sort'] = $sort;
        }
        if (!is_null($ll)) {

            $meta_description = $this->meta->setMetaForPageProgramList($ll[0]);
            $arr_breadcrumbs = $this->meta->setBreadcrumbs($ll[0]);
            $position = 1;
            foreach($arr_breadcrumbs as $key => $val) {
                $title = $val['title'];
                $link = $val['link'];
                $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "' . $title . '", "item": "' .$link . '"},';
                $position++;
            }
            $link = \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$ll[0]]], $condition);
            $application_json .= '{"@type" : "ListItem", "position":' . $position . ', "name": "' . $label_name . $page_num . '", "item": "' . $link . '"}';
        } else if($request->has('keywords'))
        {
            $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
            $position = 1;
            foreach($arr_breadcrumbs as $key => $val) {
                $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
                $position++;
            }
            $link = route('programs.list', $route_list);
            if ($request->has('keywords')) {
                $link = $link . '?keywords=' . htmlspecialchars($keywords);
            }
            $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "' . htmlspecialchars($keywords) . 'に関連する広告一覧'. $page_num .'", "item": "' . $link . '"}';
        } else if($sort !=0 ){
            $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
            $position = 1;
            foreach($arr_breadcrumbs as $key => $val) {
                $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
                $position++;
            }
            $link = route('programs.list',$route_list);
            $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "広告検索結果の広告一覧'. $page_num .'", "item": "' . $link . '"}';
        }
        else{
            $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
            $position = 1;
            foreach($arr_breadcrumbs as $key => $val) {
                $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
                $position++;
            }
            $link = route('programs.list',$route_list);
            $application_json .= '{"@type": "ListItem", "position":' . $position . ', "name": "広告検索結果'. $page_num .'", "item": "' . $link . '"}';
        }

        return view('programs.list', [
            'paginator' => $paginator,
            'condition' => $condition,
            'user_program_id_list' => $user_program_id_list,
            'label_name' => $label_name,
            'meta_description' => $meta_description,
            'arr_breadcrumbs' => $arr_breadcrumbs,
            'application_json' => $application_json,
            'sort' => $sort,
            'll' => $ll,
        ]);
    }

    /**
     * 詳細.
     * @param Program $program プログラム
     */
    public function show(Program $program, string $rid = '00')
    {
        $now = Carbon::now();

        if (Auth::check()) {
            History::setProgramHistories($program->id);
        }

        $set_date   = date('Y-m-d H:i:s');
        $review_point_management = ReviewPointManagement::where('start_at', '<=', $set_date)->where(function ($query) use ($set_date) {
            // stop_atがnullの場合（終了日が設定されていない）もしくは終了日の範囲内
            $query->whereNull('stop_at')
                ->orWhere('stop_at', '>=', $set_date);
        })->first();

        // 通常のプログラム表示
        $error_type = 0;
        if ($program->status == 1 || $program->stop_at->lt($now)) {
            // 終了または削除
            $error_type = 1;
        } elseif (!$program->isEnableDevice()) {
            // 対応外デバイスでのアクセス
            $error_type = 2;
        }

        $has_user_program = false;
        // ログインしている場合
        if (Auth::check()) {
            $user = Auth::user();

            // プログラムIDがお気に入り登録されているか確認
            $has_user_program = $user->fav_programs()
                ->wherePivot('program_id', '=', $program->id)
                ->exists();
        }

        $logrecoai = new Logrecoai();
        $logrecoai_session_id = Logrecoai::getSessionId();
        $logrecoai_user_id = Logrecoai::getUserId();
        $logrecoai_item_ids = 'pg' . $program->id;
        $num = 6;
        $device = Device::getDeviceId() == 1 ? 'pc' : 'sp';
        $logrecoai_spot_name = 'プログラム詳細_広告レコメンド';
        $recommend_data = $logrecoai->getProgramsRecommendHybrid($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_ids, $num, $device, $logrecoai_spot_name);
        if (empty($recommend_data)) {
            $recommend_data = $logrecoai->getProgramsRankingView($logrecoai_session_id, $num, $device, $logrecoai_spot_name);
        }

        $arr_breadcrumbs = [];
        $arr_breadcrumbs[] = [
            'title' => 'ホーム',
            'link' => config('app.url')
        ];

        $position = 1;
        $application_json = '';
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('programs.show', ['program' => $program->id]);
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "' . $program->title .'", "item": "' . $link . '"}';

        $data = $this->getDataAplication($program);
        $credit_card = CreditCard::where('program_id', $program->id)->first();
        $campaigns = $program->campaigns()->where('start_at', '<=', $now)
                                            ->where('stop_at', '>=', $now)
                                            ->get();
        $questions = $program->questions()->get();
        return view(
            'programs.show', [
                'error_type' => $error_type,
                'program' => $program,
                'rid' => $rid,
                'has_user_program' => $has_user_program,
                'data' => $data,
                'arr_breadcrumbs' => $arr_breadcrumbs,
                'application_json' => $application_json,
                'review_point_management' => $review_point_management,
                'recommend_data' => $recommend_data,
                'credit_card' => $credit_card,
                'campaigns' => $campaigns,
                'questions' => $questions,
            ]
        );
    }

    private function getDataAplication($program)
    {
        try {
            $data = [
                "@context" => "https://schema.org",
                "@type" => "Product",
                "name" => $program->title,
                "url" => route("programs.show", ['program' => $program]),
                "image" => $program->affiriate->img_url,
                "aggregateRating" => [
                    "@type" => "AggregateRating",
                    "ratingValue" => (float) $program->review_avg,
                    "reviewCount" => $program->review_total,
                ],
                "review" => $program->reviewsAccepted->map(function($review) {
                    return [
                        "@type" => "Review",
                        "author" => [
                            "@type" => "Person",
                            "name" => $review->reviewer,
                        ],
                        "reviewBody" => $review->message,
                    ];
                })->toArray(),
            ];
            // Replace \/ from url to / in json_encode
            $data = str_replace("\\/", "/", json_encode($data, JSON_UNESCAPED_UNICODE));

        } catch (\Throwable $th) {
            $data = json_encode([]);
        }

        return $data;
    }

    /**
     * クリック.
     * @param Request $request {@link Request}
     * @param int $program_id プログラムID
     */
    public function click(Request $request, int $program_id, string $rid = '00')
    {
        // プログラム取得
        $program = Program::ofEnableDevice()
            ->where('id', '=', $program_id)
            ->first();
        // プログラムが存在しなかった場合
        if (!isset($program->id)) {
            abort(404, 'Not Found.');
        }

        if (Auth::check()) {
            // ロック中
            Lock::authenticate(Lock::ALL_ROLE);

            // ユーザー情報を取得
            $user = Auth::user();
        } else {
            // 非ログイン状態で同意していない場合
            if ($request->input('consent', 0) != 1) {
                return view('entries.regist', [
                    'referer' => route('programs.click', ['program' => $program_id, 'rid' => $rid])]);
            }
            $user = User::find(config('app.system_user_id'));
        }

        // アフィリエイトを取得
        $affiriate = $program->affiriate;

        // クリックURL取得
        $paramName = $affiriate->asp->url_parameter_name;
        if ($paramName && $request->has($paramName)) {
            $paramValue = $request->input($paramName);
            $url = Asp::getClickUrl($affiriate->asp_id, $user, ['base_url' => $affiriate->url, 'rid' => $rid]) . '&' . $paramName . '=' . urlencode($paramValue);
        } else{
            $url = Asp::getClickUrl($affiriate->asp_id, $user, ['base_url' => $affiriate->url, 'rid' => $rid]);
        }
        // クリックURL作成に失敗した場合
        if (!isset($url)) {
            abort(404, 'Not Found.');
        }

        // クリックログ保存
        ExternalLink::addExternalLink(
            $url,
            $user->id,
            $affiriate->asp_id,
            request()->header('User-Agent'),
            Device::getIp(),
            ['asp_affiliate_id' => $affiriate->asp_affiriate_id, 'program_id' => $program->id, 'rid' => $rid]
        );

        // リダイレクト
        return redirect($url);
    }
}
