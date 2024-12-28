<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use WrapPhp;
use App\Device\Device;
use App\External\Estlier;
use App\Mainte;
use App\Question;
use App\SurveyHistory;
use App\UserAnswer;
use App\Services\Meta;

class QuestionsController extends Controller
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }
    /**
     * 外部ASPアンケート一覧.
     * @param int $page ページ
     */
    public function getList(int $page = 1)
    {
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('questions.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "アンケート", "item": "' . $link . '"},';

        $auth_user = Auth::user();
        $user_id = $auth_user->id;
        $timestamp = time();
        $today = date('Y-m-d H:i:s');

        // パネルコード：436
        // 暗号化キー：NZVu9x58KfMRvjPj
        // ユーザーのIDとタイムスタンプを連結
        $plain_text = $user_id . ':436:' . $timestamp;
        // 暗号化キー
        $pass_phrase = 'NZVu9x58KfMRvjPj';
        // OpenSSLを用いて、プレーンテキストを暗号化します// 暗号化アルゴリズムは 'bf-ecb' を使用します
        $encrypt = openssl_encrypt(
            $plain_text,
            'bf-ecb',
            $pass_phrase,
            OPENSSL_RAW_DATA
        );
        $base64_encoded = base64_encode($encrypt);

        // Base64-encoded data to hexadecimal string
        $hex_string = bin2hex(base64_decode($base64_encoded));
        
        // エストリエアンケートはスマホとタブレット以外で掲載しない
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent(request()->header('User-Agent'));

        // 回答履歴はcreated_at が半年（180日）以内のレコードを取得し表示する
        $dateLimit = Carbon::now()->subDays(180);
        $surveyHistories = SurveyHistory::where('user_id', $user_id)
                                        ->where('created_at', '>=', $dateLimit)
                                        ->orderBy('created_at', 'desc')
                                        ->get()
                                        ->unique('order_id'); // created_atが新しいものを優先して表示するため、order_idで重複を削除

        $groupedSurveys = $surveyHistories->groupBy(function($survey) {
            $date = $survey->answered_at ?? $survey->created_at; // answered_atがnullの場合、created_atを使う
            return Carbon::parse($date)->format('Ym');
        });

        if (!($agent->isPhone() || $agent->isTablet())) {
            return view('questions.index', ['user_id' => $user_id,
                                            'hex_string' => $hex_string,
                                            'groupedSurveys' => $groupedSurveys,
                                            'application_json' => $application_json,
                                            'today'=>$today,
                                        ]);
        }

        // メンテナンス中の場合
        $mainte = Mainte::ofType(Mainte::ESTLIER_TYPE)->first();
        if (isset($mainte)) {
            return view('questions.index', ['mainte_message' => $mainte->message, 'application_json' => $application_json,]);
        }

        return view('questions.index', ['user_id' => $user_id,
                                        'hex_string' => $hex_string,
                                        'groupedSurveys' => $groupedSurveys,
                                        'application_json' => $application_json,
                                        'today'=>$today,
                                    ]);
    }

    /**
     * 自社アンケート一覧.
     * @param int $page ページ
     */
    public function getMyList(int $page = 1)
    {
        //breadcrumb
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('questions.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "アンケート", "item": "' . $link . '"},';
        $position++;
        $link = route('questions.list',['page' => isset($paginator)]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "GMOポイ活アンケート", "item": "' . $link . '"}';

        // エストリエアンケートはスマホとタブレット以外で掲載しない
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent(request()->header('User-Agent'));
        if (!($agent->isPhone() || $agent->isTablet())) {
            return view('questions.list',['application_json' => $application_json]);
        }

        // メンテナンス中の場合
        $mainte = Mainte::ofType(Mainte::ESTLIER_TYPE)->first();
        if (isset($mainte)) {
            return view('questions.list', ['mainte_message' => $mainte->message,'application_json' => $application_json]);
        }

        $user_name = Auth::check() ? Auth::user()->name : null;
        $p_estlier_question_list = Estlier::getEnqueteList($user_name);

        if (!isset($p_estlier_question_list)) {
            return view('questions.list', ['mainte_message' => config('text.question_mainte'),'application_json' => $application_json]);
        }

        $limit = 20;

        $total = WrapPhp::count($p_estlier_question_list);
        // ページ数
        $page = min(max($page, 1), ceil($total / $limit));

        $estlier_question_list = array_slice($p_estlier_question_list, ($page - 1) * $limit, $limit);
        // ページネーション作成
        $paginator = new LengthAwarePaginator($estlier_question_list, $total, $limit, $page);

        return view('questions.list', ['paginator' =>  $paginator,'application_json' => $application_json]);
    }

    /**
     * アンケート詳細.
     * @param int $question_id アンケートID
     */
    public function show(int $question_id)
    {
        // アンケート取得
        $question = Question::ofEnable()
            ->where('id', '=', $question_id)
            ->firstOrFail();
        
        $user_answer = null;
        if (Auth::check()) {
            $user_answer = $question->user_answers()
                ->where('user_id', '=', Auth::user()->id)
                ->first();
        }

        $data = ['question' => $question, 'user_answer' => $user_answer];
        
        // アンケート結果取得
        $data['result_map'] = UserAnswer::select(DB::raw('count(id) as total, answer_id'))
            ->where('question_id', '=', $question_id)
            ->groupBy('answer_id')
            ->pluck('total', 'answer_id')
            ->all();

        //breadcrumb
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('questions.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "アンケート", "item": "' . $link . '"},';
        $position++;
        $link = route('questions.list',['page' => isset($paginator)]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "GMOポイ活アンケート", "item": "' . $link . '"}';
        $position++;
        $link = route('questions.show',['question' => $data['question']]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "'.$question->start_at->format('Y年m月d日').'", "item": "' . $link . '"}';


        return view('questions.show', $data)->with('application_json', $application_json);
    }

    /**
     * 月別デイリーアンケート一覧.
     * @param int $target ターゲット
     */
    public function monthly(int $target)
    {
        $target_month = Carbon::parse($target.'01')->startOfDay();

        $end = $target_month->copy()->endOfMonth();
        $yesterday = Carbon::yesterday()->endOfDay();
        $end = $end->lt($yesterday) ? $end : $yesterday;
        
        // アンケート一覧取得
        $question_list = Question::where('type', '=', 1)
            ->where('status', '=', 0)
            ->whereBetween('start_at', [$target_month, $end])
            ->orderBy('start_at', 'desc')
            ->get();
        
        return view('questions.monthly', ['target_month' => $target_month, 'question_list' => $question_list]);
    }
    
    /**
     * 回答.
     * @param Request $request {@link Request}
     */
    public function answer(Request $request)
    {
        $user_id = Auth::user()->id;
        
        // バリデーション
        $this->validate(
            $request,
            [
                'question_id' => ['required', 'integer',
                    Rule::exists('questions', 'id')->where(function ($query) {
                        $now = Carbon::now();
                        $query->where('status', '=', 0)
                            ->where('stop_at', '>', $now)
                            ->where('start_at', '<=', $now);
                    }),
                    Rule::unique('user_answers', 'question_id')->where(function ($query) use ($user_id) {
                        $query->where('user_id', '=', $user_id);
                    })
                ],
                'answer_id' => ['required', 'integer',],
            ],
            [
                'question_id.exists' => 'このアンケートは回答できません',
                'question_id.unique' => '既に回答済みです',
            ],
            [
                'question_id' => 'アンケートID',
                'answer_id' => '回答ID',
            ]
        );
        
        // アンケート取得
        $question = Question::ofEnable()
            ->where('id', '=', $request->input('question_id'))
            ->firstOrFail();
        
        // 回答できなかった場合
        if (!$question->addAnswer($user_id, $request->input('answer_id'))) {
            // 失敗の場合
            return redirect()->back();
        }

        return redirect(route('questions.show', ['question' => $question]).'#toanswer_message');
    }
    
    /**
     * メッセージ.
     * @param Request $request {@link Request}
     */
    public function answerMessage(Request $request)
    {
        $user_id = Auth::user()->id;
        
        // バリデーション
        $this->validate(
            $request,
            [
                'question_id' => ['required', 'integer',
                    Rule::exists('questions', 'id')->where(function ($query) {
                        $now = Carbon::now();
                        $query->where('status', '=', 0)
                            ->where('stop_at', '>', $now)
                            ->where('start_at', '<=', $now);
                    }),
                    Rule::exists('user_answers', 'question_id')->where(function ($query) use ($user_id) {
                        $query->where('user_id', '=', $user_id)
                            ->where('status', '=', 2);
                    })
                ],
                'sex' => ['required', 'integer',],
                'generation' => ['required', 'integer',],
                'message' => ['required', 'max:256',],
            ],
            ['question_id.exists' => 'コメントを登録できません',],
            [
                'question_id' => 'アンケートID',
                'sex' => '性別',
                'generation' => '世代',
                'message' => 'コメント'
            ]
        );
        // 回答を取得
        $user_answer = UserAnswer::where('question_id', '=', $request->input('question_id'))
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 2)
            ->firstOrFail();

        // コメントを投稿
        $user_answer->fill($request->only(['sex', 'generation', 'message']));
        $user_answer->status = 0;
        $user_answer->ip = Device::getIp();
        $user_answer->ua = $request->header('User-Agent');
        // トランザクション処理
        DB::transaction(function () use ($user_answer) {
            // 保存実行
            $user_answer->save();
            return true;
        });
        return redirect()->back();
    }

    /**
     * メッセージ一覧Ajax.
     * @param int $question_id アンケートID
     * @param int $limit 件数
     */
    public function ajaxMessage(int $question_id, int $limit = 10)
    {
        // アンケート取得
        $question = Question::ofEnable()
            ->where('id', '=', $question_id)
            ->firstOrFail();
        return view('elements.question_message_list', ['question' => $question, 'for_ajax' => true, 'limit' => $limit]);
    }
}
