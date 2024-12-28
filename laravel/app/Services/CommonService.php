<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\ExchangeRequestCashbackKey;
use App\ExchangeAccountUserKey;
use App\ExchangeRequest;
use App\ExchangeAccounts;


/**
 * ポイント交換の共通関数
 */
class CommonService
{

    /**
     * ポイント交換用のユーザのハッシュキーからユーザIDを取得
     * @param string $key ユーザのハッシュキー
     * @param int $type ポイント交換の種類ID
     */
    public function getExchangeAccountUserId($key,$type){
        $exchange_account_user_keys = ExchangeAccountUserKey::where('key', $key)->where('type',$type)->first();
        if(!empty($exchange_account_user_keys)){
            return $exchange_account_user_keys->user_id;
        }
        return false;
    }

    /**
     * ユーザIDからポイント交換用のユーザのハッシュキーを取得
     * @param int $user_id ユーザID
     * @param int $type ポイント交換の種類ID
     */
    public function getExchangeAccountUserKey($user_id,$type){
        $exchange_account_user_keys = ExchangeAccountUserKey::where('user_id', $user_id)->where('type',$type)->first();
        if(!empty($exchange_account_user_keys)){
            return $exchange_account_user_keys->key;
        }
        return false;
    }

    /**
     * ポイント交換用のユーザのハッシュキーを作成
     * @param int $user_id ユーザID
     * @param int $type ポイント交換の種類ID
     */
    public function createExchangeAccountUserKey($user_id,$type){

        $key = $this->getExchangeAccountUserKey($user_id,$type);

        if($key === false){

            $key = substr(bin2hex(random_bytes(40)),0,36);

            $count = 0;
            while(ExchangeAccountUserKey::where('key', $key)->where('type',$type)->exists()){
                $key = substr(bin2hex(random_bytes(40)),0,36);
                $count++;
                if($count > 10){
                    return false;
                }
            }
    
            ExchangeAccountUserKey::create([
                'key' => $key,
                'user_id' => $user_id,
                'type' => $type,
            ]);

            $exchange_account_user_keys = $this->getExchangeAccountUserKey($user_id,$type);
            if(!empty($exchange_account_user_keys)){
                return $exchange_account_user_keys;
            }else{
                return false;
            }

        }
        return $key;

    }

    // リファラーコードを更新
    public function updateReferrerCode($user_id,$type,$referrer_code){

        $KdolUserKey = ExchangeAccountUserKey::where('user_id', $user_id)->where('type',$type)->first();
        if(!empty($KdolUserKey)){
            $KdolUserKey->update([
                'referrer_code' => $referrer_code
            ]);
        }
    }

    /**
     * ポイント交換のキャッシュバック用ハッシュIDからポイント交換のIDを取得
     * @param string $cashback_id キャッシュバック用ハッシュID
     */
    public function getExchangeRequestId($cashback_id){
        $exchange_request_cashback_key = ExchangeRequestCashbackKey::where('cashback_id', $cashback_id)->first();
        if(!empty($exchange_request_cashback_key)){
            return $exchange_request_cashback_key->exchange_request_id;
        }
        return false;
    }


    /**
     * ポイント交換のIDからキャッシュバック用ハッシュIDを取得
     * @param int $exchange_request_id ポイント交換のID
     */
    public function getExchangeRequestCashbackKey($exchange_request_id){
        $exchange_request_cashback_key = ExchangeRequestCashbackKey::where('exchange_request_id', $exchange_request_id)->first();
        if(!empty($exchange_request_cashback_key)){
            return $exchange_request_cashback_key->cashback_id;
        }
        return false;
    }

    /**
     * ポイント交換のIDからキャッシュバック用ハッシュIDを作成
     * @param object $exchange_request ポイント交換データ
     */
    public function createExchangeRequestId($exchange_request){

        $cashback_id = $this->getExchangeRequestCashbackKey($exchange_request->id);

        if($cashback_id === false){

            $cashback_id = substr(bin2hex(random_bytes(40)),0,36);

            $count = 0;
            while(ExchangeRequestCashbackKey::where('cashback_id', $cashback_id)->exists()){
                $cashback_id = substr(bin2hex(random_bytes(40)),0,36);
                $count++;
                if($count > 10){
                    return false;
                }
            }
    
            ExchangeRequestCashbackKey::create([
                'cashback_id' => $cashback_id,
                'exchange_request_id' => $exchange_request->id,
                'user_id' => $exchange_request->user_id,
            ]);
    
            $exchange_request_cashback_key = $this->getExchangeRequestCashbackKey($exchange_request->id);
            if($exchange_request_cashback_key){
                return $exchange_request_cashback_key->cashback_id;
            }else{
                return false;
            }

        }
        return $cashback_id;

    }

    //KDOLのユーザキーを取得
    public function getUserKey($user_id,$type){

        $userKey = ExchangeAccounts::where('user_id', $user_id)->where('type',$type)->whereNull('deleted_at')->first();
        if(!empty($userKey)){
            return $userKey->number;
        }else{
            return false;
        }
    }


    //APIログ
    public function api_log($in){
        $log = new \App\ApiLog();
        $log->user_id = $in['user_id'];
        $log->type = $in['type'];
        $log->request = json_encode($in['request']);
        $log->response = json_encode($in['response']);
        $log->api_name = $in['api_name'];
        $log->status_code = $in['status_code'];
        $log->exchange_request_id = $in['exchange_request_id']??0;
        $log->save();
    }

    /**
     * JWE
     */
    public function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    public function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
    
    // alg = dir
    // enc = A256CBC-HS256
    // cipher_algo = aes-256-cbc
    // JWE専用のencrypt_key = 8G3jDFjnweurNDF88XV71224XZD6621s
    // 上記のencrypt_keyはJWEの暗号化および復号化でのみ使用され、既存のencryptkeyは従来通り同じです。
    
    public function createJWE($payload, $encryptionKey) {
       // $payloadはarrayです。
        // JWEヘッダー生成
        $header = json_encode(['alg' => 'dir', 'enc' => 'A256CBC-HS256']);
        $headerEncoded = $this->base64UrlEncode($header);
    
        // ペイロードJSONエンコード
        $payloadEncoded = json_encode($payload);
       
        // IV生成
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    
        // 暗号化
        $ciphertext = openssl_encrypt($payloadEncoded, 'aes-256-cbc', $encryptionKey, OPENSSL_RAW_DATA, $iv);
       
        // JWE生成
        $jwe = "$headerEncoded." . $this->base64UrlEncode($iv) . "." . $this->base64UrlEncode($ciphertext);
        return $jwe;
    }
    
    //複合化
    public function decryptJWE($jwe, $encryptionKey) {
        list($headerEncoded, $ivEncoded, $ciphertextEncoded) = explode('.', $jwe);
    
        $header = json_decode($this->base64UrlDecode($headerEncoded), true);
        $iv = $this->base64UrlDecode($ivEncoded);
        $ciphertext = $this->base64UrlDecode($ciphertextEncoded);
    
        // 復号化
        $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', $encryptionKey, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            return false;
        }
    
        return json_decode($decrypted, true);
    }

}