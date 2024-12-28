<?php

namespace App\Services\Line;

use GuzzleHttp\Client;
use App\Services\Line\Error;

class LinePayService
{
    private $client;
    private $error;

    public function __construct(Error $error)
    {
        $this->client = new Client([
            'base_uri' => config('line.line_pay_uri')
        ]);
        $this->error = $error;
    }

    /**
     * LINE Pay ナンバーの番号取得
     *
     * @param array $requestBody
     * @return array
     */
    public function getPaymentReferenceNo(array $requestBody): array
    {
        $url = 'user-referenceNo/get';

        $headers = $this->generateLinePayAuthorizationHeader($requestBody, $url);

        try {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => $requestBody,
            ]);
        } catch (\Exception $e) {
            \Log::error('LINE Pay:'.$e->getMessage());
            throw $e;
        }

        $responseData = json_decode($response->getBody()->getContents(), true);

        // 成功時
        if ($responseData['returnCode'] == '0000') {
            return ['referenceNo' => $responseData['info']['referenceNo']];
        }

        $errorMessage = $this->error->getErrorMessage($url, $responseData['returnCode']);
        return ['referenceNo' => '', 'errorMessage' => $errorMessage];
    }

    private function generateLinePayAuthorizationHeader($requestBody, $url)
    {
        $nonce = date_timestamp_get(date_create());
        $requestBody = json_encode($requestBody);
        $authMacText = config('line.line_pay_channel_secret') . '/v3/payments/' . $url . $requestBody . $nonce;

        $signature = base64_encode(hash_hmac('sha256', $authMacText, config('line.line_pay_channel_secret'), true));
        return [
            'content-type' => 'application/json',
            'x-line-authorization' => $signature,
            'x-line-authorization-nonce' => $nonce,
            'x-line-channelid' => config('line.line_pay_channel_id'),
        ];
    }
}
