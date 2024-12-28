<?php

namespace Tests\Feature\LinePay;

use GuzzleHttp\Client;
use Tests\TestCase;

class GetReferenceNumberTest extends TestCase
{
    // テスト実行コマンド
    // ./vendor/bin/phpunit tests/Feature/LinePay/GetReferenceNumberTest.php

    protected $client;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        
        $nonce = date_timestamp_get(date_create());

        $requestBody = [
            'channelAccessToken' => 'Your LINE Access Token',
            'agreeType' => 'Y',
        ];
        $requestBody = json_encode($requestBody);
        $authMacText = config('line.line_pay_channel_secret') . '/v3/payments/user-referenceNo/get' . $requestBody . $nonce;

        $signature = base64_encode(hash_hmac('sha256', $authMacText, config('line.line_pay_channel_secret'), true));
        $this->headers = [
            'content-type' => 'application/json',
            'x-line-authorization' => $signature,
            'x-line-authorization-nonce' => $nonce,
            'x-line-channelid' => config('line.line_pay_channel_id'),
        ];

        $this->client = new Client([
            'base_uri' => 'https://api-pay.line.me/v3/payments/'
        ]);

    }

    /**
     * 正常系テスト
     * アクセストークンは適宜変更してください
     * @test
     */
    public function testGetPaymentReferenceNo()
    {
        // リクエストボディのサンプルデータを作成
        $requestBody = [
            'channelAccessToken' => 'Your LINE Access Token',
            'agreeType' => 'Y',
        ];

        $url = 'user-referenceNo/get';

        // POSTリクエストを送信
        $response = $this->client->post($url, [
            'headers' => $this->headers,
            'json' => $requestBody,
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        
        // レスポンスのステータスコードを確認
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('0000', $responseData['returnCode']);
    }
}
