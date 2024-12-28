<?php

namespace App\Services\DigitalGift;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Psr7;


class DigitalGiftService
{
    private $client;
    private $totpService;
    private $error_code = null;
    private $error_message = null;
    private $status = null;
    private $body = null;

    public function __construct(
        TotpService $totpService
    ) {
        $this->totpService = $totpService;
        $this->client = new Client([
            'base_uri' => config('digital_gift.digital_gift_uri')
        ]);
    }

    public function createPurchaseGiftCode($exchange_request_number){
        $purchase_code = substr(hash_hmac('sha256', $exchange_request_number, config('digital_gift.purchase_key'), false), 0, 15).date('YmdHis');
        $gift_code = substr(hash_hmac('sha256', $exchange_request_number, config('digital_gift.gift_key'), false), 0, 15).date('YmdHis');
        return ['purchase_code'=>$purchase_code,'gift_code'=>$gift_code];
    }

    private function generateAuthorizationHeader($key)
    {
        $totp = $this->totpService->generate_totp($key, config('digital_gift.totp_steps'), config('digital_gift.totp_digest_algorithm'), config('digital_gift.totp_digits'));

        $headers = [
            'x-realpay-gift-api-access-token' => $totp,
            'x-realpay-gift-api-access-key' => config('digital_gift.api_key')
        ];

        return $headers;
    }

    public function getBrandData()
    {
        $url = 'brands';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        try {
            $response = $this->client->get($url, [
                'headers' => $headers,
                'http_errors' => false,
            ]);

            // HTTPステータス確認
            $this->error_code = null;
            $this->error_message = null;
            $this->status = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->body = json_decode($response->getBody(), true);


            if (isset($responseData['brands']) && $this->status == 200) {

                return $responseData;
            }

            if ($this->status != 200) {
                $this->error_code = $this->status.':Brand:'.$headers['x-realpay-gift-api-access-token'];
                if (isset($responseData['errors'])) {
                    $this->error_message = $responseData['errors'];
                }
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
    }


    public function getBrandCodeList($brand_code='')
    {
        $brands = [];
        $brand_list = $this->getBrandData();
        if ($brand_list) {
            foreach ($brand_list['brands'] as $k => $brand) {
                if(!empty($brand_code)){
                    if($brand_code == $brand['code']){
                        $brands[0] = $brand['code'];
                    }
                }else{
                    $brands[$k] = $brand['code'];
                }
            }
            return $brands;
        } else {
            return false;
        }
    }

    public function createPurchase($price, $request_id, $brand_code='')
    {

        $url = 'purchases';
        $key = config('digital_gift.secret_key');

        $bland_list = $this->getBrandCodeList($brand_code);
        if ($bland_list) {
            $headers = $this->generateAuthorizationHeader($key);

            $headers['x-realpay-gift-api-request-id'] = $request_id;
            try {
                $response = $this->client->post($url, [
                    'headers' => $headers,
                    'http_errors' => false,
                    'form_params' => [
                        'prices' => [$price],
                        'name' => config('digital_gift.purchase_name'),
                        'issuer' => config('digital_gift.purchase_issuer'),
                        'brands' => $bland_list,
                        'is_strict' => false,
                    ]
                ]);
                // HTTPステータス確認
                $this->error_code = null;
                $this->error_message = null;
                $this->status = $response->getStatusCode();
                $responseData = json_decode($response->getBody()->getContents(), true);
                $this->body = json_decode($response->getBody(), true);


                if (isset($responseData['purchase']) && $this->status == 200) {

                    return $responseData;
                }

                if ($this->status != 200) {
                    $this->error_code = $this->status.':Purchase:'.$headers['x-realpay-gift-api-access-token'];
                    if (isset($responseData['errors'])) {
                        $this->error_message = $responseData['errors'];
                    }
                    return false;
                }
            } catch (\Exception $e) {
                \Log::error('Digital Plus:' . $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function createGift($purchaseId, $price, $request_id)
    {

        $url = 'purchases/' . $purchaseId . '/gifts';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        $headers['x-realpay-gift-api-request-id'] = $request_id;
        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'http_errors' => false,
                'form_params' => [
                    'price' => $price,
                ]
            ]);

            // HTTPステータス確認
            $this->error_code = null;
            $this->error_message = null;
            $this->status = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->body = json_decode($response->getBody(), true);


            if (isset($responseData['gift']) && $this->status == 200) {

                return $responseData;
            }

            if ($this->status != 200) {
                $this->error_code = $this->status.':Gift:'.$headers['x-realpay-gift-api-access-token'];
                if (isset($responseData['errors'])) {
                    $this->error_message = $responseData['errors'];
                }
                return false;
            }

        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
    }

    public function setTemplate($purchase_id){
        $res3 = $this->setColorCode($purchase_id,'87ceeb','e0ffff');
        $res4 = $this->setImage($purchase_id);
        $res5 = $this->setImageHeader($purchase_id);
        $res6 = $this->setBanner($purchase_id);
    }

    //カラーコード設定
    public function setColorCode($purchaseId, $main, $sub)
    {
        $url = 'purchases/' . $purchaseId . '/color';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        $headers['x-realpay-gift-api-request-id'] = 'PD' . time() . rand();
        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'http_errors' => false,
                'form_params' => [
                    'main' => $main,
                    'sub' => $sub
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
        $responseData = json_decode($response->getBody()->getContents(), true);
        return $responseData;
    }

    //画像設定
    public function setImage($purchaseId)
    {
        
        $url = 'purchases/' . $purchaseId . '/image/face';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        $headers['x-realpay-gift-api-request-id'] = 'PD' . time() . rand();
        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'http_errors' => false,
                'multipart' => [
                    [
                        'name' => 'content',
                        'contents' => Psr7\Utils::tryFopen('./images/degital_gift_face.png', 'r'),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
        $responseData = json_decode($response->getBody()->getContents(), true);
        return $responseData;
    }

    //ヘッダー画像設定
    public function setImageHeader($purchaseId){
        
        $url = 'purchases/' . $purchaseId . '/image/header';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        $headers['x-realpay-gift-api-request-id'] = 'PD' . time() . rand();
        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'http_errors' => false,
                'multipart' => [
                    [
                        'name' => 'content',
                        'contents' => Psr7\Utils::tryFopen('./images/degital_gift_header.png', 'r'),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
        $responseData = json_decode($response->getBody()->getContents(), true);
        return $responseData;
    }

    //バナー画像設定
    public function setBanner($purchaseId){
        
        $url = 'purchases/' . $purchaseId . '/ad';
        $key = config('digital_gift.secret_key');

        $headers = $this->generateAuthorizationHeader($key);
        $headers['x-realpay-gift-api-request-id'] = 'PD' . time() . rand();
        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'http_errors' => false,
                'multipart' => [
                    [
                        'name'=>'redirect_url',
                        'contents'=>route('exchanges.index')
                    ],
                    [
                        'name' => 'image',
                        'contents' => Psr7\Utils::tryFopen('./images/degital_gift_Banner.png', 'r'),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Digital Plus:' . $e->getMessage());
            return false;
        }
        $responseData = json_decode($response->getBody()->getContents(), true);
        return $responseData;
    }

    public function getStatusCode()
    {
        return $this->status;
    }
    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function getErrorMessage()
    {
        if(is_array($this->error_message)){
            return implode(',', $this->error_message);
        }
        return $this->error_message;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function sendMail($email, $type, $options = [])
    {
        try {
            $mailable = new \App\Mail\DigitalGift($email, $type, $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }
    }

}
