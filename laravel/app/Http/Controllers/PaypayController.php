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
use App\Http\MaintenanceTrait;
use App\Services\Meta;
use App\Services\Paypay\PayPayService;
use App\Http\Controllers\ControllerTrait;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;



class PaypayController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    protected $paypayService;
    protected $meta;
    public function __construct(
        PayPayService $paypayService,Meta $meta
    )
    {
        $this->paypayService = $paypayService;
        $this->meta = $meta;
    }
    
    /** セッションキー. */
    const PAYPAY_SESSION_KEY = 'paypay';

    /**
     * インデックス.
     */
    public function index(Request $request) {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);
        $label = $exchange_info->label;
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

        return view('paypay.index',['application_json' => $application_json]);
    }

    /**
     * Oauth認証実行.
     */
    public function oauth(Request $request) {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);
        // paypay会員番号の取得
        $user = Auth::user();

        /** PayPay連携していないor認証エラーの場合はPayPayのユーザー認可ページへリダイレクト */
        $request_nonce = substr(bin2hex(random_bytes(40)),0,36);
        $request->session()->put('nonce', $request_nonce);
        $url = $this->paypayService->generatePayPayAuthorization($request_nonce,$user->id);//PayPayのユーザー認可をQRコードURLを取得
        if(empty($url)){
            return redirect(route('paypay.index').'?logout=1')->with('message', 'ログアウトしPayPayの認証手続きをやり直して下さい');
        }

        //nonceをDBに保存
        $this->paypayService->addNonce($user->id,$request_nonce);

        //PayPayのユーザー認可URLにリダイレクト
        return redirect($url);

    }

    /**
     * アカウント取得.PayPayからのredirect
     */
    public function account(Request $request) {

        // レスポンス取得
        $responseToken = $request->input('responseToken');
        $decodeUserAuth = $this->paypayService->decodeUserAuth($responseToken);//JWTデコード
        $nonce = $decodeUserAuth['nonce'];

        // nonceチェック&2重送信防止
        $session_nonce = $request->session()->get('nonce');
        session()->forget('nonce');
        if (empty($session_nonce) || $nonce != $session_nonce) {
            return redirect(route('paypay.index').'?logout=1')->with('message', 'ログアウトしPayPayの認証手続きをやり直して下さい');
        }
        // ユーザーIDチェック
        if($decodeUserAuth['referenceId'] != Auth::user()->id){
            return redirect(route('paypay.index').'?logout=1')->with('message', 'ログアウトしPayPayの認証手続きをやり直して下さい');
        }

        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);
        $user = Auth::user();
        $exchange_account_data = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
            ->whereNull('deleted_at')
            ->get();

        if(!$exchange_account_data->isEmpty()){//重複redirect対策
            $exchange_account = $exchange_account_data->first();
            $data = json_decode($exchange_account->data);
            if(isset($data->nonce) && isset($data->exp) && $data->nonce == $decodeUserAuth['nonce'] && $data->exp == $decodeUserAuth['exp']){
                return redirect(route('paypay.index').'?logout=1')->with('message', 'ログアウトしPayPayの認証手続きをやり直して下さい');
            }
        }

        //ユーザーから認可を得ている情報を参照
        $paypay_number = $decodeUserAuth['userAuthorizationId'];
        $check_user = $this->paypayService->checkUser($paypay_number);

        if ($check_user) {
            $profileIdentifier = $decodeUserAuth['profileIdentifier'];
            $json = [
                'profileIdentifier' => $decodeUserAuth['profileIdentifier'],
                'nonce' => $decodeUserAuth['nonce'],
                'exp' => $decodeUserAuth['exp'],
            ];
            $json_data = json_encode($json);

            $exchange_accounts = new ExchangeAccounts();
            if($exchange_account_data->isEmpty()){
                $exchange_accounts->create([
                    'type' => ExchangeRequest::PAYPAY_TYPE,
                    'user_id'  => $user->id,
                    'number'  => $paypay_number,
                    'data' =>  $json_data,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }else{
                $exchange_accounts->where('user_id', '=', $user->id)->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
                    ->whereNull('deleted_at')->update([
                    'number'  => $paypay_number,
                    'data' =>  $json_data,
                    'updated_at' => Carbon::now(),]);
            }
            
        }


        if($check_user){//ユーザー認可済みの場合
            session()->put('paypay_number', $paypay_number);
            session()->put('profileIdentifier', $profileIdentifier);
            return redirect(route('paypay.oauth_complete'));
        }else{
            session()->forget('nonce');
            return redirect(route('paypay.index').'?logout=1')->with('message', '認証に失敗しました。ログアウトしPayPayの認証手続きをやり直して下さい');
        }

    }

    /**
     * 交換.
     * @param string $number paypay会員番号
     */
    public function exchange(string $number) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);

        $user = Auth::user();
        $exchange_accounts = ExchangeAccounts::select('number', 'data')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
            ->where('number',  '=', $number)
            ->whereNull('deleted_at')
            ->get();

        if ($exchange_accounts->isEmpty()) {
            return redirect(route('paypay.index'))->with('message', 'PayPay会員番号が見つかりませんでした');
        }

        // セッションから取得
        $exchange = session()->get(self::PAYPAY_SESSION_KEY, []);
        $exchange['number'] = $number;

        $exchange_data = json_decode($exchange_accounts->first()->data);
        $exchange['profileIdentifier'] = $exchange_data->profileIdentifier;

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
        $link = route('paypay.exchange',['number' => $exchange['number']]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';
        $form_link = 'paypay.confirm';
        return view('paypay.exchange', ['point_confirm_route'=>$form_link,'exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
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
        $yens = explode(',', $exchange['yens']);
        $exchange['point'] = 0;
        foreach($yens as $yen){
            $exchange['point'] += $yen;
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
        $link = route('paypay.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('paypay.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);

        // 値を検証
        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));
        $regex = '('.implode('|', $exchange_info->yen_list).')';

        //
        $this->validate(
            $request,
            [
                'number' => ['required'],
                'yens' => ['required', 'regex:/^'.$regex.'(,'.$regex.')*$/']
            ],
            [],
            [
                'number' => 'paypay会員番号',
                'yens' => '交換ポイント',
            ]
        );

        $yens = explode(',', $request['yens']);
        $exchange['point'] = 0;
        foreach($yens as $yen){
            $exchange['point'] += $yen;
        }
        if($exchange['point']<$yen_min || $exchange['point']>$yen_max){
            $validator = Validator::make([], []);
            $validator->errors()->add('yens', $point_min.'ポイント以上、'.$point_max.'ポイント以下を選択してください');
            throw new ValidationException($validator);
        }
        
        
        $exchange = $request->only(['number', 'yens','profileIdentifier']);
        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // セッションに保存
        session()->put(self::PAYPAY_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);

        // セッションから取得
        $exchange = session()->get(self::PAYPAY_SESSION_KEY, null);
     

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     */
    public function store(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::PAYPAY_SESSION_KEY);
        // セッション削除
        session()->forget(self::PAYPAY_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        $yens = explode(',', $exchange['yens']);
        $exchange['point'] = 0;
        foreach($yens as $yen){
            $exchange['point'] += $yen;
        }
        // 申し込み情報を取得
        $exchange_request = $exchange_info->getRequest($user, $exchange['point'], Device::getIp());

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
        ->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
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
        $link = route('paypay.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('paypay.store', ['exchange_request' => $exchange_request, 'd_pt_number' => $exchange_account->d_pt_number,'application_json' => $application_json]);
    }


    /**
     * 認証後の登録完了.
     */
    public function oauthComplete(Request $request) {
        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE);
        $lable = self::checkExchangeInfo(ExchangeRequest::PAYPAY_TYPE)->label;
        $paypay_number = session()->get('paypay_number');
        $profileIdentifier = session()->get('profileIdentifier');

        if(!$paypay_number || !$profileIdentifier){
            return redirect(route('paypay.index').'?logout=1')->with('message', 'ログアウトしPayPayの認証手続きをやり直して下さい'); 
        }

        // セッション削除
        session()->forget('paypay_number');
        session()->forget('profileIdentifier');
        session()->forget('nonce');


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
        $link = route('paypay.oauth_complete');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $lable . '", "item": "' . $link . '"}';


        return view('paypay.oauth_complete', ['paypay_number' => $paypay_number,'profileIdentifier' => $profileIdentifier, 'application_json' => $application_json]);
    }

}