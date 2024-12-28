<?php
namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\ExchangeInfo;
use App\ExchangeRequest;
use App\History;

use App\Device\Device;
use App\External\DotMoney;
use App\Http\MaintenanceTrait;
use App\Services\Meta;

class DotMoneyController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /** ドットマネーセッションキー. */
    const DOT_MONEY_SESSION_KEY = 'dot_money';

    /**
     * インデックス.
     */
    public function index() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);
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
        $link = route('dot_money.index');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('dot_money.index',['application_json' => $application_json]);
    }

    /**
     * Oauth認証実行.
     */
    public function oauth() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        $user = Auth::user();
        $url = DotMoney::getContentAuthUrl($user->name);
        // 履歴登録
        History::addHistory(History::DOT_MONEY_TYPE, ['url' => $url, 'user_id' => $user->id]);
        return redirect($url);
    }


    /**
     * Oauth認証実行.
     */
    public function setting() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        $user = Auth::user();
        $url = DotMoney::getContentAuthDotmoneySettingUrl($user->name);
        // 履歴登録
        History::addHistory(History::DOT_MONEY_TYPE, ['url' => $url, 'user_id' => $user->id]);
        return redirect($url);
    }

    /**
     * アカウント取得.
     */
    public function account() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        $user = Auth::user();

        // 口座番号を取得
        $dot_money = DotMoney::getShow(true, $user->name);

        // 実行
        $res = $dot_money->execute();

        // 失敗した場合
        if (!$res) {
            return redirect(route('dot_money.index'))
                ->with('message', '口座番号が見つかりませんでした');
        }

        // 口座番号
        $account_number = $dot_money->getResponse()->account_number ?? null;

        // 失敗した場合
        if (!isset($account_number)) {
            return redirect(route('dot_money.index'))
                ->with('message', '口座番号が見つかりませんでした');
        }


        // 交換画面へリダイレクト
        return redirect(route('dot_money.exchange', ['number' => $account_number]));
    }

    /**
     * 交換.
     * @param string $number 口座番号
     */
    public function exchange(string $number) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        // セッションから取得
        $exchange = session()->get(self::DOT_MONEY_SESSION_KEY, []);
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
        $link = route('dot_money.exchange',['number' => $exchange['number']]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('dot_money.exchange', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
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
        $link = route('dot_money.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('dot_money.confirm', ['exchange_info' => $exchange_info, 'exchange' => $exchange,'application_json' => $application_json]);
    }

    /**
     * 確認.
     */
    public function postConfirm(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);



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
                'number' => ['required', 'regex:/^[0-9]{16}$/'],
                'yen' => ['required', 'integer', 'min:'.$yen_min, 'max:'.$yen_max],
            ],
            [],
            [
                'number' => 'ドットマネー口座番号',
                'yen' => '交換ポイント',
            ]
        );

        $exchange = $request->only(['number', 'yen']);
        // 交換情報ID
        $exchange['exchange_info_id'] = $exchange_info->id;

        // セッションに保存
        session()->put(self::DOT_MONEY_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     */
    public function getConfirm() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        // セッションから取得
        $exchange = session()->get(self::DOT_MONEY_SESSION_KEY, null);
        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     */
    public function store(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::DOT_MONEY_POINT_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $exchange = session()->get(self::DOT_MONEY_SESSION_KEY);
        // セッション削除
        session()->forget(self::DOT_MONEY_SESSION_KEY);

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
        $link = route('dot_money.store');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('dot_money.store', ['exchange_request' => $exchange_request,'application_json' => $application_json]);
    }
}
