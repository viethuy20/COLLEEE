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
use App\Services\Kdol\KdolService;
use App\Http\Controllers\ControllerTrait;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\CommonService;
use App\ExchangeAccountRequest;




class KdolController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    protected $kdolService;
    protected $meta;
    private $commonService;
    private $exchangeAccountRequest;
    public function __construct(
        KdolService $kdolService,Meta $meta,CommonService $commonService,ExchangeAccountRequest $exchangeAccountRequest

    )
    {
        $this->kdolService = $kdolService;
        $this->meta = $meta;
        $this->commonService = $commonService;
        $this->exchangeAccountRequest = $exchangeAccountRequest;
    }
    
    /** セッションキー. */
    const KDOL_SESSION_KEY = 'kdol';

    /**
     * インデックス.
     */
    public function index(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);
        
        $user = Auth::user();
        $key = '';
        $gmo_id = '';
        $kdol_number = '';
        $check_user = false;
        $exchange_account_data = ExchangeAccounts::select('number', 'data','user_id')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::KDOL_TYPE)
            ->whereNull('deleted_at')
            ->first();

        if(!empty($exchange_account_data)){//すでに認可済みの場合
             //ユーザーから認可を得ている情報を参照
            $kdol_number = $exchange_account_data->number;
            $check_user = $this->kdolService->checkUserKey($exchange_account_data->user_id,$kdol_number);

        }

        //ポーリング用のセッションキーを生成
        $session_key = Str::random(40);
        $this->exchangeAccountRequest->createExchangeAccountRequest($user->id,ExchangeRequest::KDOL_TYPE,$session_key);
        session()->put('exchange_key',$session_key);
        $gmo_id = $this->commonService->createExchangeAccountUserKey($user->id,ExchangeRequest::KDOL_TYPE);
    

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
        $link = route('kdol.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $label . '", "item": "' . $link . '"}';

        return view('kdol.index',['application_json' => $application_json,'kdol_user'=>$check_user,'kdol_number'=>$kdol_number,'session_key'=>$session_key,'user_key'=>$gmo_id]);
    }

    /**
     * Oauth認証実行.
     */
    public function oauth(Request $request) {

        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);
        // kdol会員番号の取得
        $user = Auth::user();
        $exchange_account_data = ExchangeAccounts::select('number', 'data','user_id')
            ->from('exchange_accounts')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::KDOL_TYPE)
            ->whereNull('deleted_at')
            ->first();

        if(!empty($exchange_account_data)){//すでに認可済みの場合
             //ユーザーから認可を得ている情報を参照
            $kdol_number = $exchange_account_data->number;
            $check_user = $this->kdolService->checkUserKey($exchange_account_data->user_id,$kdol_number);
            
            if ($check_user) {
                return redirect(route('kdol.exchange'));
            }
        }

        $url = $this->kdolService->createKdolKeyUrl($user->id);//kdolのユーザー認可をQRコードURLを取得

        if(empty($url)){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }


        //kdolのユーザー認可URLにリダイレクト
        return redirect()->away($url);

    }

    /**
     * アカウント取得.kdolからのレスポンスが来た後の処理
     */
    public function account(Request $request) {
        
        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);

        // レスポンス取得
        $response = $request->all();

        $user = Auth::user();
        
        $session_key = session()->get('exchange_key');
        $exchange_account_request = ExchangeAccountRequest::select('*')
            ->from('exchange_account_requests')
            ->where('user_id', '=', $user->id)
            ->where('type',  '=', ExchangeRequest::KDOL_TYPE)
            ->where('session_key',  '=', $session_key)
            ->first();

        if(empty($exchange_account_request)){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }

        if(empty($exchange_account_request->response)){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }

        //レスポンスの検証
        $responseToken = json_decode($exchange_account_request->response, true);
        if($this->kdolService->checkAccountResponse($responseToken) === false){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }

        $user_id = $this->commonService->getExchangeAccountUserId($responseToken['gmo_id'],ExchangeRequest::KDOL_TYPE);

        // ユーザーIDチェック
        if(!$user_id || $user_id != Auth::user()->id){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }

        

        if($responseToken['status'] != 0){//認証エラーの場合
            if($responseToken['status']==3){
                return redirect(route('kdol.index').'?logout=1')->with('message', 'このアカウントはすでに連携されています');
            }

            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }

        if($responseToken['insert_type']==1){//連携解除の場合
            return redirect(route('kdol.release_complete'));
        }

        //ユーザーから認可を得ている情報を参照
        $kdol_number = $responseToken['kdol_id'];
        $check_user = $this->kdolService->checkUserKey($user_id,$kdol_number);

        if ($check_user) {
            
            session()->put('kdol_number',$kdol_number);

            return redirect(route('kdol.oauth_complete'));
            
        }else{
            
            return redirect(route('kdol.index').'?logout=5')->with('message', '認証に失敗しました。ログアウトしkdolの認証手続きをやり直して下さい');
        }

    }

    /**
     * 交換.
     * @param string $number 会員番号
     */
    public function exchange(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);
        $user = Auth::user();

        $number = session()->get('kdol_number');
        $exchangeAccount = new ExchangeAccounts();
        $exchange_accounts = $exchangeAccount->getExchangeAccounts($user->id,ExchangeRequest::KDOL_TYPE,$number);

        if ($exchange_accounts === null) {
            return redirect(route('kdol.index'))->with('message', 'kdol会員番号が見つかりませんでした');
        }

        if(!empty($exchange_accounts)){//すでに認可済みの場合
            //ユーザーから認可を得ている情報を参照
            $exchange_account_data = $exchange_accounts->first();
           $kdol_number = $exchange_account_data->number;
           $check_user = $this->kdolService->checkUserKey($user->id,$kdol_number);
           if(!$check_user){
                return redirect(route('kdol.index'))->with('message', 'kdol会員番号が見つかりませんでした');
           }
       }

        // セッションから取得
        $exchange = session()->get(self::KDOL_SESSION_KEY, []);
        
        
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
        $link = route('kdol.exchange');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';
        $form_link = 'kdol.confirm';
        return view('kdol.exchange', ['point_confirm_route'=>$form_link,'exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
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
        $exchange_point = 0;
        foreach($yens as $yen){
            $exchange_point += $yen;
        }
        $exchange['point'] = $exchange_point * config('exchange.yen_rate') / config('exchange.point.'.ExchangeRequest::KDOL_TYPE.'.yen.rate');
       

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
        $link = route('kdol.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('kdol.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);
        
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
                //'number' => ['required'],
                'yens' => ['required', 'regex:/^'.$regex.'(,'.$regex.')*$/']
            ],
            [],
            [
                //'number' => 'kdol会員番号',
                'yens' => '交換ポイント',
            ]
        );
        
        $yens = explode(',', $request['yens']);
        $exchange_point = 0;
        foreach($yens as $yen){
            $exchange_point += $yen;
        }
        $exchange['point'] = $exchange_point * config('exchange.yen_rate') / config('exchange.point.'.ExchangeRequest::KDOL_TYPE.'.yen.rate');
        
        if($exchange['point']<$yen_min || $exchange['point']>$yen_max){
            $validator = Validator::make([], []);
            $validator->errors()->add('yens', $point_min.'ポイント以上、'.$point_max.'ポイント以下を選択してください');
            throw new ValidationException($validator);
        }
        
        
        $exchange = $request->only(['number', 'yens','profileIdentifier']);
        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // セッションに保存
        session()->put(self::KDOL_SESSION_KEY, $exchange);
        
        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);

        // セッションから取得
        $exchange = session()->get(self::KDOL_SESSION_KEY, null);
     

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     */
    public function store(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::KDOL_SESSION_KEY);
        // セッション削除
        session()->forget(self::KDOL_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        $exchangeAccount = new ExchangeAccounts();
        $exchange_accounts = $exchangeAccount->getExchangeAccounts($user->id,ExchangeRequest::KDOL_TYPE);

        if ($exchange_accounts === null) {
            return redirect(route('kdol.index'))->with('message', 'kdol会員番号が見つかりませんでした');
        }

        if(!empty($exchange_accounts)){//すでに認可済みの場合
            //ユーザーから認可を得ている情報を参照
            $exchange_account_data = $exchange_accounts->first();
           $kdol_number = $exchange_account_data->number;
           $check_user = $this->kdolService->checkUserKey($user->id,$kdol_number);
           if(!$check_user){
                return redirect(route('kdol.index'))->with('message', 'kdol会員番号が見つかりませんでした');
           }
       }

        $yens = explode(',', $exchange['yens']);
        $exchange_point = 0;
        foreach($yens as $yen){
            $exchange_point += $yen;
        }
        $exchange['point'] = $exchange_point * config('exchange.yen_rate') / config('exchange.point.'.ExchangeRequest::KDOL_TYPE.'.yen.rate');
       
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
        ->where('type',  '=', ExchangeRequest::KDOL_TYPE)
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
        $link = route('kdol.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('kdol.store', ['exchange_request' => $exchange_request, 'd_pt_number' => $exchange_account->d_pt_number,'application_json' => $application_json]);
    }


    /**
     * 認証後の登録完了.
     */
    public function oauthComplete(Request $request) {
        // 交換情報をチェック
        self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE);
        $lable = self::checkExchangeInfo(ExchangeRequest::KDOL_TYPE)->label;
        $kdol_number = session()->get('kdol_number');

        if(!$kdol_number){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい'); 
        }

        // セッション削除
        session()->forget('kdol_number');


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
        $link = route('kdol.oauth_complete');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $lable . '", "item": "' . $link . '"}';


        return view('kdol.oauth_complete', ['kdol_number' => $kdol_number, 'application_json' => $application_json]);
    }


    //連携解除
    public function release(){
        $user = Auth::user();
        $url = $this->kdolService->createKdolKeyUrl($user->id,1);//kdolのユーザー認可をQRコードURLを取得

        if(empty($url)){
            return redirect(route('kdol.index').'?logout=1')->with('message', 'ログアウトしkdolの認証手続きをやり直して下さい');
        }


        //kdolのユーザー認可URLにリダイレクト
        return redirect()->away($url);

    }
    public function releaseConfirm(Request $request){

        $responseToken = $response = $request->all();
        if(isset($response['responseToken'])){
            $responseToken = $this->kdolService->decodeUserAuth($response['responseToken']);
        }
        //ログを残す
        $url = $this->kdolService->createKdolKeyUrl(Auth::user()->id,1);
        $this->commonService->api_log(['user_id'=>Auth::user()->id,'exchange_request_id'=>0,'type'=>ExchangeRequest::KDOL_TYPE,'request'=>$url,'response'=>$responseToken,'api_name'=>'/proc/proc_get_gmo_nikko.kdol','status_code'=>$responseToken['status']??'']);
        
        if($responseToken['status']==0){
            return redirect(route('kdol.release_complete'));
        }elseif($responseToken['status']==4){
            return redirect(route('kdol.index').'?logout=1')->with('message', '連携しているKDOLアカウントでKDOLのサイトにログインしてください');
        }else{
            return redirect(route('kdol.index').'?logout=1')->with('message', 'KDOLアカウントの連携解除に失敗しました');
        }
    }

    public function releaseComplete(Request $request){

        return view('kdol.release_complete');
    }

    public function account_check(string $user_key, string $session_key){
        $user_id = $this->commonService->getExchangeAccountUserId($user_key,ExchangeRequest::KDOL_TYPE);
        if($user_id){
            $exchange_account_request = ExchangeAccountRequest::select('*')
            ->from('exchange_account_requests')
            ->where('user_id', '=', $user_id)
            ->where('type',  '=', ExchangeRequest::KDOL_TYPE)
            ->where('session_key',  '=', $session_key)
            ->first();
            if(!empty($exchange_account_request)){
                if($exchange_account_request->response !== ''){
                    return response()->json(['status' => 1]);
                }
                return response()->json(['status' => 0]);
            }else{
                return response()->json(['status' => 2]);//error
            }
            
        }

    }

}