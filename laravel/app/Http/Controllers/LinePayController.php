<?php

namespace App\Http\Controllers;

use App\Device\Device;
use App\ExchangeInfo;
use App\ExchangeRequest;
use App\User;
use App\Http\MaintenanceTrait;
use App\Services\Line\LineService;
use App\Services\Line\LinePayService;
use Auth;
use Illuminate\Http\Request;
use App\Services\Meta;

class LinePayController extends Controller
{
    /** @var LineService */
    private $line;

    private $linePayService;
    private $meta;
    use ControllerTrait;
    use MaintenanceTrait;

    /** LINEPayセッションキー */
    public const LINE_PAY_SESSION_KEY = 'line_pay';

    public const LINE_PAY_FEE = 50;

    public function __construct(LineService $lineService, LinePayService $linePayService,Meta $meta)
    {
        $this->line = $lineService;
        $this->linePayService = $linePayService;
        $this->meta = $meta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);
        $label = self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE)->label;
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
        $link = route('line_pay.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $label . '", "item": "' . $link . '"}';

        return view('line_pay.index',['application_json' => $application_json]);
    }

    /**
     *
     *
     * @param Request $request
     * @return void
     */
    public function oauth(Request $request)
    {
        // 交換情報を取得
        self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);
        // LINEIDの取得
        $user = Auth::user();
        $line_id = $user->line_id;
        $line_account = $user->line_account;

        try {
            $check = $this->line->verifyLineAccessToken($line_account->token);
        } catch (\Exception $e) {
            // アクセストークンの有効期限が切れた場合やLINE 連携してないとき
            \Log::error('LINE Access Token Verification:'.$e->getCode() . ':' . $e->getMessage());
            $check = false;
        }

        if (!$check) {
            // トークンの有効でない場合は再度ログインさせる
            $lineService = new \App\Services\Line\LineService();
            $urlLine = $lineService->getLoginBaseUrl();
            return redirect($urlLine);
        } else {
            // 交換画面へリダイレクト
            return redirect(route('line_pay.exchange', ['line_id' => $line_id]));
        }
    }

    /**
     * 交換.
     * @param string $number LINEID
     */
    public function exchange($line_id)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);

        $user = Auth::user();
        $user_line_accounts = User::select('id')
            ->from('users')
            ->where('id', '=', $user->id)
            ->where('line_id', '=', $line_id)
            ->first();

        if (is_null($user_line_accounts)) {
            return redirect(route('line_pay.index'))->with('message', 'LINEIDが見つかりませんでした');
        }

        // セッションから取得
        $exchange = session()->get(self::LINE_PAY_SESSION_KEY, []);
        $exchange['line_id'] = $line_id;

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
        $link = route('line_pay.exchange',['line_id' => $exchange['line_id']]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('line_pay.exchange', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);

    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);

        // 値を検証
        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));

        // バリエーション
        $this->validate(
            $request,
            [
                'line_id' => ['required'],
                'yen' => ['required', 'integer', 'min:'.$yen_min, 'max:'.$yen_max],
                'consent' => ['required', 'boolean']
            ],
            [
                'consent.required' => 'LINE Payへの交換申請には同意にチェックを入れて下さい。',
                'consent.boolean' => 'LINE Payへの交換申請には同意にチェックを入れて下さい。',
            ],
            [
                'line_id' => 'ラインID',
                'yen' => '交換ポイント',
                'consent' => '同意チェック',
            ]
        );

        $exchange = $request->only(['line_id', 'yen']);
        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // LINE Payナンバーの取得
        $line_access_token = $user->line_account->token;

        $requestBody = [
            'channelAccessToken' => $line_access_token,
            'agreeType' => 'Y',
        ];
        $result  =  $this->linePayService->getPaymentReferenceNo($requestBody);

        if ($result['referenceNo'] == '') {
            return redirect()->back()->withErrors(['reference_no_error' => $result['errorMessage']]);
        }

        $user->line_account->referenceNo = $result['referenceNo'];
        $user->line_account->save();

        // セッションに保存
        session()->put(self::LINE_PAY_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm()
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);

        // セッションから取得
        $exchange = session()->get(self::LINE_PAY_SESSION_KEY, null);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認view取得.
     * @param ExchangeInfo $exchange_info 交換情報
     * @param array|NULL $exchange 交換内容
     */
    public function getConfirmView(ExchangeInfo $exchange_info, $exchange)
    {
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
        $link = route('line_pay.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('line_pay.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::LINE_PAY_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::LINE_PAY_SESSION_KEY);
        // セッション削除
        session()->forget(self::LINE_PAY_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        // 申し込み情報を取得
        $exchange_request = $exchange_info->getRequest($user, $exchange['yen'], Device::getIp());

        // 手数料を引く（実際に送金される金額）
        $exchange_request->yen -= static::LINE_PAY_FEE;

        if (!$exchange_request->createExchangeRequest()) {
            // 交換申し込みに失敗した場合
            return redirect(route('exchanges.index'));
        }

        // メール送信を実行
        $options = ['exchange_request_number' => $exchange_request->number];
        try {
            $mailable = new \App\Mail\Colleee($user->email, 'exchange', $options);
            \Mail::send($mailable);
        } catch(\Exception $e) {
        }

        $line_id = $user->line_id;
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
        $link = route('line_pay.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('line_pay.store', ['exchange_request' => $exchange_request, 'line_id' => $line_id,'application_json' => $application_json]);
    }
}
