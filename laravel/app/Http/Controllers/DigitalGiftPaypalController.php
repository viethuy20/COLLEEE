<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DigitalGift\TotpService;
use App\Services\DigitalGift\DigitalGiftService;
use App\Http\MaintenanceTrait;
use App\Http\Controllers\ControllerTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\ExchangeInfo;
use App\ExchangeAccounts;
use App\ExchangeRequest;
use Auth;
use App\Services\Meta;
use App\Device\Device;
use Carbon\Carbon;


class DigitalGiftPaypalController extends Controller
{

    const DIGITAL_GIFT_PAYPAL_SESSION_KEY = 'digital_gift_paypal';
    const DIGITAL_GIFT_PAYPAL_CODE = 'paypal';

    use ControllerTrait, MaintenanceTrait;

    private $totpService;
    private $digitalGiftService;
    private $meta;

    public function __construct(
        TotpService $totpService,
        DigitalGiftService $digitalGiftService,
        Meta $meta
    ) {
        $this->meta = $meta;
        $this->totpService = $totpService;
        $this->digitalGiftService = $digitalGiftService;
    }

    
    /**
     * 交換.
     * @param string $number
     */
    public function index()
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE);

        $user = Auth::user();

        // セッションから取得
        $exchange = session()->get(self::DIGITAL_GIFT_PAYPAL_SESSION_KEY, []);

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach ($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('paypal.exchange', []);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('digital_gift_paypal.exchange', ['exchange_info' => $exchange_info, 'application_json' => $application_json, 'exchange' => $exchange]);
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
        $yens = explode(',', $exchange['yens']);
        $exchange['point'] = 0;
        foreach ($yens as $yen) {
            $exchange['point'] += $yen;
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach ($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('paypal.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('digital_gift_paypal.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange, 'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE);

        // 値を検証
        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));


        $regex = '(' . implode('|', $exchange_info->yen_list) . ')';
        $this->validate(
            $request,
            ['yens' => ['required', 'regex:/^' . $regex . '(,' . $regex . ')*$/']],
            [],
            [
                'yens' => '交換ポイント',
            ]
        );

        $exchange = $request->only(['yens']);
        $yens = explode(',', $request['yens']);
        $exchange['point'] = 0;
        foreach ($yens as $yen) {
            $exchange['point'] += $yen;
        }
        if ($exchange['point'] < $yen_min || $exchange['point'] > $yen_max) {
            $validator = Validator::make([], []);
            $validator->errors()->add('yens', $point_min . 'ポイント以上、' . $point_max . 'ポイント以下を選択してください');
            throw new ValidationException($validator);
        }

        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // セッションに保存
        session()->put(self::DIGITAL_GIFT_PAYPAL_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm()
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE);

        // セッションから取得
        $exchange = session()->get(self::DIGITAL_GIFT_PAYPAL_SESSION_KEY, null);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     */
    public function store(Request $request)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE);

        $user = Auth::user();


        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::DIGITAL_GIFT_PAYPAL_SESSION_KEY);
        // セッション削除
        session()->forget(self::DIGITAL_GIFT_PAYPAL_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        // 申し込み情報を取得
        $exchange_request = $exchange_info->getRequest($user, $exchange['point'], Device::getIp());

        if (!$exchange_request->createExchangeRequest()) {
            // 交換申し込みに失敗した場合
            return redirect(route('exchanges.index'));
        }

        $purchaseGiftCode = $this->digitalGiftService->createPurchaseGiftCode($exchange_request->number);
        $purchase_code = $purchaseGiftCode['purchase_code'];
        $gift_code = $purchaseGiftCode['gift_code'];

        $res = $this->digitalGiftService->createPurchase($exchange['point'], $purchase_code,self::DIGITAL_GIFT_PAYPAL_CODE);
        $status_code = $this->digitalGiftService->getStatusCode();

        if (!$res && $status_code == 401) {
            $count = 0;
            while (!$res) {
                $res = $this->digitalGiftService->createPurchase($exchange['point'], $purchase_code,self::DIGITAL_GIFT_PAYPAL_CODE);
                if (!$res) {
                    sleep(1);
                }
                $count++;
                if ($count >= 5) {
                    break;
                }
            }
        }


        // エラーコード取得
        $status_code = $this->digitalGiftService->getStatusCode();
        $body = $this->digitalGiftService->getBody();
        $error_code = $this->digitalGiftService->getErrorCode();
        $error_message = $this->digitalGiftService->getErrorMessage();

        $exchange_request->response_code = $error_code ? $error_code . ' : ' . $error_message : $status_code;
        $exchange_request->response = ['body' => $body, 'purchase_code' => $purchase_code];
        $exchange_request->request_level = 1;
        $exchange_request->requested_at = Carbon::now();


        if ($res) {
            $this->digitalGiftService->setTemplate($res['purchase']['id']);
            $res2 = $this->digitalGiftService->createGift($res['purchase']['id'], $exchange['point'], $gift_code);
            $status_code = $this->digitalGiftService->getStatusCode();

            if (!$res2 && $status_code == 401) {
                $count = 0;
                while (!$res2) {
                    $res2 = $this->digitalGiftService->createGift($res['purchase']['id'], $exchange['point'], $gift_code);
                    if (!$res2) {
                        sleep(1);
                    }
                    $count++;
                    if ($count >= 5) {
                        break;
                    }
                }
            }

            // エラーコード取得
            $status_code = $this->digitalGiftService->getStatusCode();
            $body = $this->digitalGiftService->getBody();
            $error_code = $this->digitalGiftService->getErrorCode();
            $error_message = $this->digitalGiftService->getErrorMessage();

            $exchange_request->response_code = $error_code ? $error_code . ' : ' . $error_message : $status_code;
            $exchange_request->response = ['body' => $body, 'purchase_code' => $purchase_code, 'gift_code' => $gift_code];
            $exchange_request->request_level = 1;
            $exchange_request->requested_at = Carbon::now();

            if ($res2) {
                $exchange_request->approvalRequest();
            } else {
                $exchange_request->rollbackRequest();
                return redirect(route('paypal.index') . '?logout=1')->with('message', 'ログアウトしPayPalポイントの手続きをやり直して下さい');
            }
        } else {
            $exchange_request->rollbackRequest();
            return redirect(route('paypal.index') . '?logout=1')->with('message', 'ログアウトしPayPalポイントの手続きをやり直して下さい');
        }


        // メール送信を実行
        $options = ['user' => $user, 'digital_gift_url' => $res2['gift']['url'], 'exchange_request_number' => $exchange_request->number,'code_name' => 'PayPal'];
        $this->digitalGiftService->sendMail($user->email, 'gift', $options);

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach ($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"},';
        $position++;
        $link = route('paypal.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('digital_gift_paypal.store', ['exchange_request' => $exchange_request, 'application_json' => $application_json]);
    }
}
