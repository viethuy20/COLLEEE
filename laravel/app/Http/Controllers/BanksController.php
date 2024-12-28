<?php
namespace App\Http\Controllers;

use Auth;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

use App\Bank;
use App\BankAccount;
use App\ExchangeInfo;
use App\ExchangeRequest;
use App\Services\Meta;

use App\Device\Device;
use App\Http\MaintenanceTrait;

class BanksController extends Controller
{
    use ControllerTrait, MaintenanceTrait;
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /** 銀行振り込みセッションキー. */
    const BANK_TRANSFER_SESSION_KEY = 'bank_transfer';

    /**
     * 口座選択.
     */
    public function index() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        $account = Auth::user()->bank_account;
        // 口座情報が未登録、または銀行支店が見つからなかった場合
        if (!isset($account) || !isset($account->bank_branch)) {
            return redirect(route('banks.bank_list'));
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';

        return view('banks.index',['application_json' => $application_json]);
    }

    /**
     * 銀行一覧.
     */
    public function bankList() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.bank_list');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';


        return view('banks.bank_list', ['bank_list' => Bank::ofStable()->get(),'application_json' => $application_json]);
    }

    /**
     * 支店一覧.
     * @param string $bank_code 銀行コード
     */
    public function branchList(string $bank_code) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        // 銀行取得
        $bank = Bank::ofStable()
            ->where('code', '=', $bank_code)
            ->first();
        // 銀行支店一覧取得
        $bank_branch_list = $bank->branches()
            ->ofStable()
            ->get();

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.branch_list',['bank' => $bank->code]);
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';

        return view('banks.branch_list', ['bank' => $bank, 'bank_branch_list' => $bank_branch_list,'application_json' => $application_json]);
    }

    /**
     * 振込申し込み.
     * @param string $bank_code 銀行コード
     * @param string $branch_code 銀行支店コード
     */
    public function createAccount($bank_code = null, $branch_code = null) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        $transfer = null;
        if (!isset($bank_code) || !isset($branch_code)) {
            // 銀行振り込み情報取得
            $transfer = session()->get(self::BANK_TRANSFER_SESSION_KEY);

            // 銀行コード、または支店コードが空の場合
            if (!isset($transfer['bank_code']) || !isset($transfer['branch_code'])) {
                return redirect(route('exchanges.index'));
            }

            $bank_code = $transfer['bank_code'];
            $branch_code = $transfer['branch_code'];
        }

        // 銀行取得
        $bank = Bank::ofStable()
            ->where('code', '=', $bank_code)
            ->first();
        // 銀行支店取得
        $bank_branch = $bank->branches()
            ->ofStable()
            ->where('code', '=', $branch_code)
            ->first();

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.create_account',['bank' => $bank->code, 'bank_branch' => $bank_branch->code]);
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';

        return view('banks.create_account', [
            'exchange_info' => $exchange_info, 'bank' => $bank, 'bank_branch' => $bank_branch, 'application_json' => $application_json, 'transfer' => $transfer,
        ]);
    }

    /**
     * 振込申し込み.
     */
    public function selectAccount() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        // 銀行振り込み情報取得
        $transfer = session()->get(self::BANK_TRANSFER_SESSION_KEY);

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.select_account');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';


        return view('banks.select_account', ['exchange_info' => $exchange_info, 'transfer' => $transfer,'application_json' => $application_json]);
    }

    /**
     * 確認view取得.
     * @param ExchangeInfo $exchange_info 交換情報
     * @param array|NULL $transfer 振り込み情報
     */
    private function getConfirmTransferView(ExchangeInfo $exchange_info, $transfer) {
        // 振り込み情報が空、または交換条件が変わった場合
        if (!isset($transfer) || $transfer['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        // 銀行取得
        $bank = Bank::ofStable()
            ->where('code', '=', $transfer['bank_code'])
            ->first();

        // 銀行支店取得
        $bank_branch = $bank->branches()
            ->ofStable()
            ->where('code', '=', $transfer['branch_code'])
            ->first();

        $user = Auth::user();
        $charge = $user->has_ticket ? 0 : $bank->getCharge($user);
        // 手数料込金額
        $transfer['yen2'] = $transfer['yen'] - $charge;
        // 交換ポイント
        $transfer['point'] = $transfer['yen'];

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.confirm_transfer');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';

        return view('banks.confirm_transfer', [
            'exchange_info' => $exchange_info, 'bank' => $bank, 'bank_branch' => $bank_branch, 'transfer' => $transfer,'application_json' => $application_json
        ]);
    }

    /**
     * 振込申し込み確認.
     * @param Request $request {@link Request}
     */
    public function postConfirmTransfer(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        $user = Auth::user();

        $point_min = $exchange_info->min_point;
        $yen_min = $exchange_info->chargeYen($point_min);
        $point_max = $user->max_exchange_point;
        $yen_max = min(config('exchange.yen_max'), $exchange_info->chargeYen($point_max));

        //
        $this->validate(
            $request,
            [
                'account_id' => [
                    'nullable', 'integer',
                    Rule::exists('bank_accounts', 'id')->where(function($query) use($user) {
                        $query->where('status', '=', 0)
                            ->where('user_id', '=', $user->id);
                    }),
                ],
                'bank_code' => ['nullable', 'required_without:account_id', 'regex:/^[0-9]{4}$/'],
                'branch_code' => ['nullable', 'required_without:account_id', 'regex:/^[0-9]{3}$/'],
                'number' => ['nullable', 'required_without:account_id', 'regex:/^[0-9]{7}$/'],
                'first_name' => ['nullable', 'required_without:account_id'],
                'last_name' => ['nullable', 'required_without:account_id'],
                'first_name_kana' => ['nullable', 'required_without:account_id', 'custom_ebank_name:24'],
                'last_name_kana' => ['nullable', 'required_without:account_id', 'custom_ebank_name:24'],
                'yen' => ['required', 'integer', 'min:'.$yen_min, 'max:'.$yen_max,],
            ],
            [
                'custom_ebank_name' => ':attributeは全角カタカナ24文字以内で入力してください。',
                'yen.max' => '交換ポイントが所持ポイントを超えています',
            ],
            [
                'account_id' => '銀行口座ID',
                'bank_code' => '銀行コード',
                'branch_code' => '支店コード',
                'number' => '口座番号',
                'first_name' => '姓',
                'last_name' => '名',
                'first_name_kana' => 'セイ',
                'last_name_kana' => 'メイ',
                'yen' => '選択金額',
            ]
        );

        if ($request->has('account_id')) {
            $account = $user->bank_account;

            // 銀行口座ID
            $transfer = $account->only([
                'bank_code', 'branch_code', 'number', 'first_name', 'last_name',
                'first_name_kana', 'last_name_kana',
            ]);
            $transfer['account_id'] = $account->id;
        } else {
            // 銀行情報
            $transfer = $request->only([
                'bank_code', 'branch_code', 'number', 'first_name', 'last_name',
                'first_name_kana', 'last_name_kana',
            ]);
        }

        // 交換情報ID
        $transfer['exchange_info_id'] = $exchange_info->id;
        // 選択金額
        $transfer['yen'] = $request->input('yen');

        // セッションに保存
        session()->put(self::BANK_TRANSFER_SESSION_KEY, $transfer);

        return $this->getConfirmTransferView($exchange_info, $transfer);
    }

    /**
     * 振込申し込み確認.
     */
    public function getConfirmTransfer() {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);
        // セッションから取得
        $transfer = session()->get(self::BANK_TRANSFER_SESSION_KEY, null);

        return $this->getConfirmTransferView($exchange_info, $transfer);
    }

    /**
     * 振込申し込み実行.
     * @param Request $request {@link Request}
     */
    public function storeTransfer(Request $request) {
        // 交換情報を取得
        $exchange_info = self::checkExchangeInfo(ExchangeRequest::BANK_TYPE);

        // トークン再発行
        session()->regenerateToken();
        // セッションから取得
        $transfer = session()->get(self::BANK_TRANSFER_SESSION_KEY, null);
        // セッション削除
        session()->forget(self::BANK_TRANSFER_SESSION_KEY);

        // セッションが空、または交換条件が変わった場合
        if (!isset($transfer) || $transfer['exchange_info_id'] != $exchange_info->id) {
            return redirect(route('exchanges.index'));
        }

        $user = Auth::user();

        if (isset($transfer['account_id'])) {
            // 口座を取得
            $bank_account = BankAccount::where('id', '=', $transfer['account_id'])
                ->where('status', '=', 0)
                ->where('user_id', '=', $user->id)
                ->first();

            if (!isset($bank_account->id)) {
                // 口座取得に失敗した場合
                return redirect(route('exchanges.index'));
            }
        } else {
            // 口座を作成
            $bank_account = BankAccount::getDefault($user->id, $transfer['bank_code'],
                    $transfer['branch_code'], $transfer['number'], $transfer['first_name'],
                    $transfer['last_name'], $transfer['first_name_kana'], $transfer['last_name_kana']);

            if (!$bank_account->createBankAccount()) {
                // 口座作成に失敗した場合
                return redirect(route('exchanges.index'));
            }
        }
        // 申し込み情報を取得
        $exchange_request = $exchange_info->getBankRequest($user, $transfer['yen'], Device::getIp(), $bank_account);

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
            \Log::info($e->getMessage());
        }

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('exchanges.index');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';
        $position++;
        $link = route('banks.store_transfer');
        $application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "金融機関振込", "item": "' . $link . '"}';

        //
        return view('banks.store_transfer', ['exchange_request' => $exchange_request]);
    }
}
