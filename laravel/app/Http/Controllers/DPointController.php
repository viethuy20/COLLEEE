<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\ExchangeInfo;
use App\ExchangeAccounts;
use App\ExchangeRequest;
use App\Device\Device;
use App\External\DPoint;
use App\Http\MaintenanceTrait;
use App\Services\Meta;


class DPointController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }


    /** dポイントセッションキー. */
    const D_POINT_SESSION_KEY = 'd_point';

    /**
     * インデックス.
     */
    public function index(Request $request) {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);
        $label = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE)->label;

        $data = [];
        if($request->input('logout')) {
            $data['logout_url'] =  config('d_point.LOGOUT_URL'). '?serviceurl='. route('d_point.index');
        }
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $label . '", "item": "' . $link . '"}';

        return view('d_point.index', $data)->with('application_json', $application_json);
    }

    /**
     * Oauth認証実行.
     */
    public function oauth(Request $request) {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);
        // dポイントクラブ会員番号の取得
        $user = Auth::user();
        $exchange_accounts = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::D_POINT_TYPE)
            ->whereNull('deleted_at')
            ->get();

        if ($exchange_accounts->isEmpty()) {
            $nonce = (string) Str::uuid();
            $request->session()->put('nonce', $nonce);
            $state = (string) Str::uuid();
            $request->session()->put('state', $state);
            $url = DPoint::getContentAuthUrl($state, $nonce);
            return redirect($url);
        } else {
            $exchange_account = $exchange_accounts->first();
            // 交換画面へリダイレクト
            return redirect(route('d_point.exchange', ['number' => $exchange_account->number]));
        }
    }

    /**
     * アカウント取得.
     */
    public function account(Request $request) {

        // stateチェック
        $state = session()->get('state');
        if ($state != $request->input('state')) {
            session()->forget('state');
            return redirect(route('d_point.index').'?logout=1')->with('message', 'ログアウトしdポイントの認証手続きをやり直して下さい');
        }

        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);
        $user = Auth::user();
        $exchange_accounts = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::D_POINT_TYPE)
            ->whereNull('deleted_at')
            ->get();

        // dポイント連携済みの場合、ポイント交換ページに遷移
        if (!$exchange_accounts->isEmpty()) {
            $exchange_account = $exchange_accounts->first();
            return redirect(route('d_point.exchange', ['number' => $exchange_account->number]));
        }

        $params = [];
        $params['grant_type'] = 'authorization_code';
        $params['code'] = $request->input('code');
        $params['redirect_uri'] = config('d_point.REDIRECT_URI');

        $d_point = new DPoint();
        // 実行
        $res = $d_point->execute($params);

        if (isset($res) && is_array($res)) {
            $request->session()->put('d_pt_number', $res['d_pt_number']);
            $request->session()->put('sub', $res['sub']);
            return redirect(route('d_point.oauth_confirm'));
        } else {
            session()->forget('d_pt_number');
            return redirect(route('d_point.index'). '?logout=1')->with('message', 'ログアウトしdポイントの認証手続きをやり直して下さい');
        }
    }

    /**
     * 交換.
     * @param string $number dポイントクラブ会員番号
     */
    public function exchange(string $number) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);

        $user = Auth::user();
        $exchange_accounts = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::D_POINT_TYPE)
            ->where('number',  '=', $number)
            ->whereNull('deleted_at')
            ->get();

        if ($exchange_accounts->isEmpty()) {
            return redirect(route('d_point.index'))->with('message', 'dポイントクラブ会員番号が見つかりませんでした');
        }

        // セッションから取得
        $exchange = session()->get(self::D_POINT_SESSION_KEY, []);
        $exchange['number'] = $number;

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.exchange',['number' => $exchange['number']]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('d_point.exchange', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * 確認view取得.
     * @param ExchangeInfo $exchange_info 交換情報
     * @param array|NULL $exchange 交換内容
     */
    public function getConfirmView(ExchangeInfo $exchange_info, $exchange) {
        // 交換内容が空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect($exchange_info->url);
        }
        // 消費ポイント
        $exchange['point'] = $exchange['yen'];

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('d_point.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);

        // 値を検証
        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));

        //
        $this->validate(
            $request,
            [
                'number' => ['required', 'regex:/^[0-9]{12}$/'],
                'yen' => ['required', 'integer', 'min:'.$yen_min, 'max:'.$yen_max],
            ],
            [],
            [
                'number' => 'dポイントクラブ会員番号',
                'yen' => '交換ポイント',
            ]
        );

        $exchange = $request->only(['number', 'yen']);
        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // セッションに保存
        session()->put(self::D_POINT_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);

        // セッションから取得
        $exchange = session()->get(self::D_POINT_SESSION_KEY, null);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     */
    public function store(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::D_POINT_SESSION_KEY);
        // セッション削除
        session()->forget(self::D_POINT_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        // 申し込み情報を取得
        $exchange_request = $exchange_info->getRequest($user, $exchange['yen'], Device::getIp());

        if (!$exchange_request->createExchangeRequest()) {
            // 交換申し込みに失敗した場合
           return redirect(route('exchanges.index'));
        }

        // メール送信を実行
        $options = ['exchange_request_number' => $exchange_request->number];
        try{
            $mailable = new \App\Mail\Colleee($user->email, 'exchange', $options);
            \Mail::send($mailable);
        } catch(\Exception $e){
        }

        $exchange_accounts = ExchangeAccounts::select('number', 'data')
        ->from('exchange_accounts')
        ->where('user_id', '=', $user->id)
        ->where('type',  '=', ExchangeRequest::D_POINT_TYPE)
        ->whereNull('deleted_at')
        ->get();

        $exchange_account = $exchange_accounts->first();

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('d_point.store', ['exchange_request' => $exchange_request, 'd_pt_number' => $exchange_account->d_pt_number,'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function oauthConfirm() {
        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);
        $lable = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE)->label;
        $exchange_type = ExchangeRequest::D_POINT_TYPE;
        $user = Auth::user();
        $d_pt_number = session()->get('d_pt_number');
        $sub= session()->get('sub');

        // セッションが切れていた場合、認証のやり直しを促す
        if (empty($d_pt_number) || empty($sub)) {
            return redirect(route('d_point.index'). '?logout=1')->with('message', 'ログアウトしdポイントの認証手続きをやり直して下さい');
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.oauth_confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $lable . '", "item": "' . $link . '"}';

        return view('d_point.oauth_confirm', [
            'exchange_type' => $exchange_type,
            'user_id' => $user->id,
            'sub' => $sub,
            'd_pt_number' => $d_pt_number,
            'application_json' => $application_json
        ]);
    }

    /**
     * 完了.
     */
    public function oauthComplete(Request $request) {
        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE);
        $lable = self::checkExchangeInfo(ExchangeRequest::D_POINT_TYPE)->label;
        // セッション削除
        session()->forget('d_pt_number');
        session()->forget('sub');

        $d_pt_number = $request->input('d_pt_number');
        $sub = $request->input('sub');
        $json_data = json_encode(['sub' => $sub]);
        $type = $request->input('type');
        $user_id = $request->input('user_id');
        $exchange_accounts = new ExchangeAccounts();

        $exchange_accounts_data = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user_id)
            ->where('type',  '=', ExchangeRequest::D_POINT_TYPE)
            ->whereNull('deleted_at')
            ->get();
        $exist_flag = false;
        if ($exchange_accounts_data->isEmpty()) {
            $exchange_accounts->create([
                'type' => $type,
                'user_id'  => $user_id,
                'number'  => $d_pt_number,
                'data' =>  $json_data,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $exist_flag = true;
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('d_point.oauth_complete');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $lable . '", "item": "' . $link . '"}';


        return view('d_point.oauth_complete', ['d_pt_number' => $d_pt_number, 'exist_flag' => $exist_flag,'application_json' => $application_json]);
    }
}
