<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Device\Device;
use App\ExchangeInfo;
use App\ExchangeRequest;
use App\Http\MaintenanceTrait;
use App\Services\Meta;

class GiftCodesController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }


    /** ギフトコードセッションキー. */
    const GIFT_CODE_SESSION_KEY = 'gift_code';

    private static $VIEW_MAP = [
        ExchangeRequest::AMAZON_GIFT_TYPE => 'amazon',
        ExchangeRequest::ITUNES_GIFT_TYPE => 'itunes',
        ExchangeRequest::PEX_GIFT_TYPE => 'pex',
        ExchangeRequest::NANACO_GIFT_TYPE => 'nanaco',
        ExchangeRequest::EDY_GIFT_TYPE => 'edy',
        ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE => 'google_play',
        ExchangeRequest::WAON_GIFT_TYPE => 'waon',
        ExchangeRequest::PONTA_GIFT_TYPE => 'ponta',
        ExchangeRequest::PSSTICKET_GIFT_TYPE => 'pssticket',
    ];

    public static function getTypeList() : array
    {
        return array_keys(self::$VIEW_MAP);
    }

    /**
     * 交換.
     * @param int $type 種類
     */
    public function index(int $type)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo($type);

        // セッションから取得
        $exchange = session()->get(self::GIFT_CODE_SESSION_KEY);
        if (isset($exchange['exchange_info_id'])) {
            // 交換情報の種類が変更された場合
            $cur_exchange_info = ExchangeInfo::find($exchange['exchange_info_id']);
            if ($cur_exchange_info->type != $type) {
                $exchange = null;
            }
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
        $link = route('gift_codes.index',['type'=> $exchange_info->type]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        return view('gift_codes.'.self::$VIEW_MAP[$type], ['exchange_info' => $exchange_info, 'application_json' => $application_json, 'exchange' => $exchange]);
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
        $yen_list = explode(',', $exchange['yens']);
        $yen_total_map = [];
        $total_point = 0;
        foreach ($yen_list as $yen) {
            if (!isset($yen_total_map[$yen])) {
                $yen_total_map[$yen] = 0;
            }
            $yen_total_map[$yen] = $yen_total_map[$yen] + 1;
            if($exchange_info->type == ExchangeRequest::PEX_GIFT_TYPE) {
                $pex_rate = config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate');
                $total_point = $total_point + ($exchange_info->chargePoint($yen) / $pex_rate);
            } else {
                $total_point = $total_point + $exchange_info->chargePoint($yen);
            }
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
        $link = route('gift_codes.confirm',['type'=> $exchange_info->type]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';

        $exchange['point'] = $total_point;
        $exchange['yen_total_map'] = $yen_total_map;
        return view('gift_codes.confirm', ['exchange_info' => $exchange_info, 'application_json' => $application_json, 'exchange' => $exchange]);
    }

    /**
     * 確認.
     * @param Request $request {@link Request}
     * @param int $type 種類
     */
    public function postConfirm(Request $request, int $type)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo($type);
        $regex = '('.implode('|', $exchange_info->yen_list).')';
        //
        $this->validate(
            $request,
            ['yens' => ['required', 'regex:/^'.$regex.'(,'.$regex.')*$/']],
            [],
            ['yens' => '交換ポイント']
        );

        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));


        $yen_list = explode(',', $request->input('yens'));
        sort($yen_list, SORT_NUMERIC);
        $yen_total = array_sum($yen_list);
        if ($type === ExchangeRequest::PEX_GIFT_TYPE) {
            $exPexRate = config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate');
            $yen_total = $yen_total /$exPexRate;
        }
        // 交換上限を超える場合

        if ($yen_total > $yen_max) {
            return back()
                ->withInput()
                ->withErrors(['yens' => sprintf('最高交換ポイント（%sポイント）を上回っています', number_format($point_max))]);
        }
        if ($yen_total < $yen_min) {
            return back()
                ->withInput()
                ->withErrors(['yens' => sprintf('最低交換ポイント（%sポイント）を下回っています', number_format($point_min))]);
        }
        // 交換内容
        $exchange = ['yens' => implode(',', $yen_list), 'exchange_info_id' => $exchange_info->id];

        // セッションに保存
        session()->put(self::GIFT_CODE_SESSION_KEY, $exchange);

        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 確認.
     * @param int $type 種類
     */
    public function getConfirm(int $type)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo($type);

        // セッションから取得
        $exchange = session()->get(self::GIFT_CODE_SESSION_KEY, null);
        return $this->getConfirmView($exchange_info, $exchange);
    }

    /**
     * 申し込み実行.
     * @param Request $request {@link Request}
     * @param int $type 種類
     */
    public function store(Request $request, int $type)
    {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo($type);


        // トークン再発行
        session()->regenerateToken();

        // セッションから取得
        $exchange = session()->get(self::GIFT_CODE_SESSION_KEY);

        // セッション削除
        session()->forget(self::GIFT_CODE_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($exchange) || $exchange['exchange_info_id'] != $exchange_info->id) {

            return redirect($exchange_info->url);
        }

        $yen_list = explode(',', $exchange['yens']);

        $user = Auth::user();
        $ip = Device::getIp();

        $exchange_request_list = collect();
        foreach ($yen_list as $yen) {
            // 申し込み情報を取得

            if($exchange_info->type == ExchangeRequest::PEX_GIFT_TYPE) {
                $pex_rate = config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate');
                $exchange_request = $exchange_info->getRequest($user, $yen / $pex_rate, $ip);
            } else {
                $exchange_request = $exchange_info->getRequest($user, $yen , $ip);
            }
            $exchange_request_list->push($exchange_request);
        }

        // 申し込み処理を実行
        $res_exchange_request_list = ExchangeRequest::createExchangeRequestList($exchange_request_list);
        if ($res_exchange_request_list->isEmpty()) {
            // 交換申し込みに失敗した場合
            return redirect(route('gift_codes.index', ['type' => $type]));
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
        $link = route('gift_codes.store',['type'=> $exchange_info->type]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $exchange_info->label . '", "item": "' . $link . '"}';


        return view('gift_codes.store', ['exchange_info' => $exchange_info, 'application_json' => $application_json,
            'exchange_request_list' => $res_exchange_request_list]);
    }
}
