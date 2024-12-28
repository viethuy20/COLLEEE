<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExchangeAccounts;
use App\ExchangeRequest;
use Carbon\Carbon;
use App\PaypayErrorLogs;
use App\Services\Paypay\PayPayService;

class PaypayWebhookController extends Controller
{
    protected $paypayService;
    public function __construct(
        PayPayService $paypayService
    ) {
        $this->paypayService = $paypayService;
    }
    /**
     * PayPayからのユーザ関連のWebhookを処理します。
     */
    public function user(Request $request)
    {
        $data = $request->all();
        //ユーザ認証成功時の処理
        if (isset($data['notification_type']) && !empty($data['expiry']) && !empty($data['referenceId']) && !empty($data['userAuthorizationId'])) {

            $user_id = $data['referenceId'];
            $paypay_number = $data['userAuthorizationId'];


            //ユーザ認証成功時の処理
            if ($data['notification_type'] == "customer.authroization.succeeded") {

                $exchange_accounts_select = ExchangeAccounts::select('number', 'data')
                    ->from('exchange_accounts')
                    ->where('user_id', '=', $user_id)
                    ->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
                    ->whereNull('deleted_at')
                    ->get();

                $exchange_account_data = $exchange_accounts_select->first();
                $exchange_account_data_json = json_decode($exchange_account_data->data);

                //nonceが異なる場合は処理しない
                if (isset($exchange_account_data_json->nonce) && $exchange_account_data_json->nonce == $data['nonce']) {


                    if (!$exchange_accounts_select->isEmpty()) { //連携している場合は古い既存のデータを更新して登録する

                        if (isset($exchange_account_data_json->exp) && isset($exchange_account_data_json->userAuthorizationId) && $exchange_account_data_json->nonce == $data['nonce'] && $exchange_account_data_json->userAuthorizationId == $data['userAuthorizationId'] && $exchange_account_data_json->exp == $data['expiry']) {
                            //すでに登録されていた場合は何もしない
                            echo 'OK';
                            return;
                        }
                    }

                    $json = [
                        'profileIdentifier' => $data['profileIdentifier'],
                        'nonce' => $data['nonce'],
                        'exp' => $data['expiry'],
                    ];
                    $json_data = json_encode($json);

                    $exchange_accounts = new ExchangeAccounts();
                    if ($exchange_accounts_select->isEmpty()) {
                        $exchange_accounts->create([
                            'type' => ExchangeRequest::PAYPAY_TYPE,
                            'user_id'  => $user_id,
                            'number'  => $paypay_number,
                            'data' =>  $json_data,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    } else {
                        $exchange_accounts->where('user_id', '=', $user_id)->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
                            ->whereNull('deleted_at')->update([
                                'number'  => $paypay_number,
                                'data' =>  $json_data,
                                'updated_at' => Carbon::now(),
                            ]);
                    }
                }
            }
        }
        echo 'OK';
    }
}
