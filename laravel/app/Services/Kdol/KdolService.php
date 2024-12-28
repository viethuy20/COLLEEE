<?php
namespace App\Services\Kdol;

use GuzzleHttp\Client;
use App\ExchangeRequestCashbackKey;
use App\ExchangeAccountUserKey;
use App\ExchangeRequest;
use App\Services\CommonService;

class KdolService
{

    private $encrypt_code;
    private $client;
    private $commonService;
    private $status_code;
    private $error_code;
    private $errorMessage;
    private $response_code;

    public function __construct(
        CommonService $commonService
    )
    {
        $this->commonService = $commonService;
        $this->encrypt_code = config('kdol.encrypt_code');
        $this->response_code = config('kdol.response_code');

        $this->client = new Client([
            'base_uri' => config('kdol.base_uri')
        ]);

    }

    /**
     * データ整合性確保のためのデータを暗号化
     * @param string $hash_data 暗号化するデータ
     */
    public function getEncrypt($hash_data='')
    {
        $encrypt_code = config('kdol.encrypt_code');//暗号化キー

        return md5($hash_data.$encrypt_code);
    }

    public function checkHash($in,$hash){

        if(self::getEncrypt($in) == $hash){
            return true;
        }
        return false;
    }

    public function generateAuthorizationHeader(){
        $headers = [
            'content-type' => 'application/json',
        ];
        return $headers;
    }

    //ユーザ認証情報の復号
    public function decodeUserAuth($encodedString)
    {
        $key = config('kdol.jwe_encrypt_code');
        return $this->commonService->decryptJWE($encodedString, $key);
    }

    //ユーザ認証情報の暗号化
    public function encodeUserAuth($encodedData)
    {
        $key = config('kdol.jwe_encrypt_code');
        return $this->commonService->createJWE($encodedData, $key);
    }

    //アカウント連携のレスポンスチェック
    public function checkAccountResponse($response){
        if(!$response['gmo_id'] || !$response['kdol_id'] || !$response['hash'] || $response['insert_type']==='' || $response['status']===''){
           return false;
        }else{
            if(self::checkHash($response['gmo_id'].$response['insert_type'].$response['kdol_id'].$response['status'], $response['hash'])){
                return true;
            }
        }
        return false;
    }

    public function checkChashbackResponse($response){
        if(!$response['gmo_id'] || !$response['kdol_id'] || !$response['hash'] || $response['status']==='' || !$response['point'] || !$response['transaction_id'] || !$response['transaction_time']){
           return false;
        }else{
            if(self::checkHash($response['gmo_id'].$response['kdol_id'].$response['status'].$response['point'].$response['transaction_id'].$response['transaction_time'], $response['hash'])){
                return true;
            }
        }
        return false;
    }

    /**
     * Kdolのユーザ連携用のURLを生成
     */
    public function createKdolKeyUrl($user_id,$insert_type=0){

        $requesturl = config('kdol.api_url.proc_get_gmo_nikko');

        $redirectUrl = config('kdol.redirect_url');
        $gmo_id = $this->commonService->createExchangeAccountUserKey($user_id,ExchangeRequest::KDOL_TYPE);

        if(!$gmo_id){
            return false;
        }

        if($insert_type===1){//連携解除
            $redirectUrl = route('kdol.release_confirm');
        }

        $request = [
            'gmo_id' => $gmo_id,
            'insert_type' => $insert_type,
            'redirectUrl'=> $redirectUrl,
            'hash' => self::getEncrypt($gmo_id.$insert_type),
        ];
        
        return config('kdol.base_uri').$requesturl.'?'.http_build_query($request,'&');
    }


    //連携済みのアカウントの有効性チェック
    public function checkUserKey($user_id,$kdol_key){
        
        $requesturl = config('kdol.api_url.proc_get_gmo_nikko_status');
        
        $headers = $this->generateAuthorizationHeader();
        $user_key = $this->commonService->getExchangeAccountUserKey($user_id,ExchangeRequest::KDOL_TYPE);

        if(empty($user_key)){
            return false;
        }
       

        $request = [
            'headers' => $headers,
            'http_errors' => false,
            'query' => [
                'gmo_id' => $user_key,
                'kdol_id' => $kdol_key,
                'hash' => self::getEncrypt($user_key.$kdol_key),
            ],
        ];

        try {
            $response = $this->client->get($requesturl, $request);
            $responseData = json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }

        //ログ記録
        $this->commonService->api_log(['user_id'=>$user_id,'exchange_request_id'=>0,'type'=>ExchangeRequest::KDOL_TYPE,'request'=>$request,'response'=>$responseData,'api_name'=>$requesturl,'status_code'=>$responseData['status']??'']);
        
        if(isset($responseData['status']) && isset($responseData['gmo_id']) && isset($responseData['kdol_id']) && isset($responseData['code']) && isset($responseData['hash'])){
            $this->status_code = $responseData['status'];

            if($responseData['status'] === 0){//success
                //ハッシュチェック
                if(!self::checkHash($responseData['gmo_id'].$responseData['kdol_id'].$responseData['code'].$responseData['status'], $responseData['hash'])){
                    return false;
                }
    
                if($responseData['code'] === 1 || $responseData['code'] === 2){//連携済みor別のユーザで連携済み
                    return true;
                }elseif($responseData['code'] === 0){//未連携
                    return false;
                }
            }else{
                $this->error_code = $responseData['status'];
                $this->errorMessage = $this->response_code[$this->status_code];
                return false;
            }
        }else{
            return false;
        }
        
        
    }


}