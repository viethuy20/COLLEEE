<?php
namespace App\Services\Paypay;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Each;
use App\Services\Paypay\Error;
use App\ExchangeAccounts;
use App\ExchangeRequest;
use App\PaypayLogs;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PayPayService
{
    private $client;
    private $error;
    private $api_key;
    private $paypay_secret;
    private $paypay_merchant_id;
    private $error_code = null;
    private $error_code_id = null;
    private $errorMessage = null;
    private $status_code = null;

    public function __construct(Error $error)
    {
        $this->api_key = config('paypay.paypay_api_key');
        $this->paypay_secret = config('paypay.paypay_secret');
        $this->paypay_merchant_id = config('paypay.paypay_merchant_id');

        $this->client = new Client([
            'base_uri' => config('paypay.paypay_base_uri')
        ]);

        $this->error = $error;
    }

    public function decodeUserAuth($encodedString)
    {
        $key = new Key(base64_decode($this->paypay_secret), 'HS256');
        return (array) JWT::decode($encodedString, $key);
    }
    /**
     * PayPayAPIのheaderを作成
     */
    private function generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce,$request_method = 'POST')
    {

        $api_key = config('paypay.paypay_api_key');
        $paypay_secret = config('paypay.paypay_secret');
        $epoch = date_timestamp_get(date_create());//現在のエポックタイムスタンプ
        $content_type = 'application/json';
        
        

        if($request_method == 'POST'){
            $hash = hash('md5',$content_type.$requestBody,true);//ok
            $hash_hmac = $hash = base64_encode($hash);//ok
        }else{
            $content_type = $hash = 'empty';
            $hash_hmac = "empty";
        }
        
        
        $DELIMITER = "\n";
        $hmacData =  $requesturl.$DELIMITER.$request_method.$DELIMITER.$nonce.$DELIMITER.$epoch.$DELIMITER.$content_type.$DELIMITER.$hash;
        
        $signature = base64_encode(hash_hmac('sha256', $hmacData, $paypay_secret, true));
        $authHeader = "hmac OPA-Auth:" . $api_key . ":" . $signature . ":" . $nonce . ":" . $epoch . ":" . $hash_hmac;
        
        $headers = [
            'content-type' => $content_type,
            'Authorization' => $authHeader,
            'X-ASSUME-MERCHANT' => config('paypay.paypay_merchant_id'),
        ];

        return $headers;
    }

    public function generatePayPayAuthorization($request_nonce,$user_id){

        $requesturl = '/v1/qr/sessions';
        $requestBody = [
            'scopes' => ['cashback'],
            'nonce'=> $request_nonce,
            'redirectUrl' => config('paypay.paypay_redirect_url'),
            'redirectType'=> 'APP_DEEP_LINK',
            'referenceId'=> $user_id,
        ];
        $requestBody = json_encode($requestBody);
        $nonce = substr(uniqid(bin2hex(random_bytes(1))),0,8);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce);

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'body' => $requestBody,
        ];

        try {
            $response = $this->client->post($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }

        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS'){
           return $responseData['data']['linkQRCodeURL'];
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log($user_id,0,0,$requesturl,$this->error_code,json_encode($request),$responseData);
            return false;
        }
    }

    /**
     * PayPayのユーザー認証チェック
     */
    public function checkUser($paypayUserId){
        $requesturl = '/v2/user/authorizations';
        $nonce = substr(bin2hex(random_bytes(16)),0,8);
        $requestBody = '';
        //$requestBody = json_encode($requestBody);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce,'GET');

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'query' => [
                'userAuthorizationId' => $paypayUserId,
            ],
        ];
        try {
            $response = $this->client->get($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS' && $responseData['data']['status'] == 'ACTIVE' && $responseData['data']['expireAt'] > time()){
           return true;
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log(null,$paypayUserId,0,$requesturl,$request,$responseData);
            return false;
        }
    }

    /**
     * PayPayのマスクされたユーザーの電話番号を取得
     */
    public function getUserPhone($paypayUserId){
        $requesturl = '/v2/user/profile';
        $nonce = substr(bin2hex(random_bytes(16)),0,8);
        $requestBody = [
            'userAuthorizationId'=> $paypayUserId
        ];
        $requestBody = json_encode($requestBody);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce);

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'body' => $requestBody,
        ];
        try {
            $response = $this->client->get($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            
            echo 'Error: ' . $e->getMessage();
            return false;
        }
        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS'){
           return $responseData['data']['phoneNumber'];
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log(null,$paypayUserId,0,$requesturl,$request,$responseData);
            return false;
        }
    }

    public function unlinkUser($paypayUserId){
        $requesturl = '/v2/user/authorizations';
        $nonce = substr(bin2hex(random_bytes(16)),0,8);
        $requestBody = [
            'userAuthorizationId'=> $paypayUserId
        ];
        $requestBody = json_encode($requestBody);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce);

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'body' => $requestBody,
        ];
        try {
            $response = $this->client->delete($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS'){
           return true;
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log(null,$paypayUserId,0,$requesturl,$request,$responseData);
            return false;
        }
    }

    //PayPayのキャッシュバックをキャンセルする
    public function reverseCashback($merchantCashbackId,$user_id,$amount){
        
        $requesturl = '/v2/cashback_reversal';
        $nonce = substr(bin2hex(random_bytes(16)),0,8);
        $requestBody = [
            'merchantCashbackReversalId'=> $merchantCashbackId,
            'merchantCashbackId'=> $merchantCashbackId,
            'amount'=>  ['amount'=> $amount, 'currency'=> 'JPY'],
            'requestedAt'=> date_timestamp_get(date_create()),
        ];
        $requestBody = json_encode($requestBody);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce);

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'body' => $requestBody,
        ];

        try {
            $response = $this->client->post($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }

        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS'){
           return true;
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log($user_id,0,0,$requesturl,$request,$responseData);
            return false;
        }
    }

    //PayPayのキャッシュバックをキャンセルの確認
    public function checkReverseCashback($merchantCashbackReversalId,$user_id){
        $requesturl = '/v2/cashback_reversal/'.$merchantCashbackReversalId;
        $nonce = substr(bin2hex(random_bytes(16)),0,8);
        $requestBody = '';
        $requestBody = json_encode($requestBody);
        $headers = $this->generatePayPayAuthorizationHeader($requestBody,$requesturl,$nonce);

        $request = [
            'headers' => $headers,
            'http_errors' => false
        ];

        try {
            $response = $this->client->get($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }

        $this->status_code = $responseData['resultInfo']['code'];
        if($responseData['resultInfo']['code'] == 'SUCCESS'){
           return true;
        }else{
            $this->error_code = $responseData['resultInfo']['code'];
            $this->error_code_id = $responseData['resultInfo']['codeId'];
            $this->errorMessage = $responseData['resultInfo']['message'];
            $this->paypay_log($user_id,0,0,$requesturl,$request,$responseData);
            return false;
        }
    }

    public function paypay_log($user_id,$paypayUserId,$exchange_request_id,$requesturl,$request,$response){
        if($user_id==null){
            $account = ExchangeAccounts::where('number', $paypayUserId)->where('type', ExchangeRequest::PAYPAY_TYPE)->where('deleted_at', null)->first();
            $user_id = $account->user_id;
        }
        PaypayLogs::create([
            'user_id'=>$user_id,
            'exchange_request_id'=>$exchange_request_id,
            'api_name' => $requesturl,
            'status_code' => $this->status_code,
            'request'=>json_encode($request),
            'response'=>json_encode($response),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function addNonce($user_id,$nonce){
        
        $exchange_accounts_select = ExchangeAccounts::select('number', 'data')
                    ->from('exchange_accounts')
                    ->where('user_id', '=', $user_id)
                    ->where('type',  '=', ExchangeRequest::PAYPAY_TYPE)
                    ->whereNull('deleted_at')
                    ->get();
        if ($exchange_accounts_select->isEmpty()) {
            $exchange_accounts = new ExchangeAccounts();
            $exchange_accounts->create([
                'type' => ExchangeRequest::PAYPAY_TYPE,
                'user_id'  => $user_id,
                'number'  => '',
                'data' =>  json_encode(['nonce'=>$nonce]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }else{
            $exchange_accounts_select->first()->update(['data' => json_encode(['nonce'=>$nonce])]);
        }
    }
}