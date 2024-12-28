<?php

namespace App\Services\Line;


use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Log;

class LineService
{

    public function getLoginBaseUrl()
    {
        $currentTime = Carbon::now()->getTimestamp();
        $url = config('line.line_authorize_uri') . '?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . config('line.line_login_channel_id');
        $url .= '&redirect_uri=' . route('login.line.callback');
        $url .= '&state=' . $currentTime;
        $url .= '&scope=openid%20profile%20real_name%20gender%20birthdate%20phone%20address%20email';

        return $url;
    }

    public function getLineToken($code)
    {
        
        $client = new Client();
        $response = $client->post(config("line.line_token_uri"), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('login.line.callback'),
                'client_id' => config('line.line_login_channel_id'),
                'client_secret' => config('line.line_login_channel_secret')
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserProfile($token)
    {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $response = $client->get(config('line.line_profile_uri'), [
            'headers' => $headers
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    // LINEアクセストークンを検証する
    public function verifyLineAccessToken($accessToken)
    {
        $client = new Client();
        $response = $client->get(config("line.line_verify_uri"), [
            'query' => [
                'access_token' => $accessToken,
            ],
        ]);
        
        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            return false;
        }
    }

    // IDトークンを検証する
    public function verifyIDToken($idToken) {
        $client = new Client();
        $response = $client->post(config("line.line_verify_uri"), [
            'form_params' => [
                'id_token' => $idToken,
                'client_id' => config('line.line_login_channel_id'),
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function refreshAccessToken($contentType) {
        $client = new Client();
        $response = $client->post(config("line.line_refresh_accessToken"), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'refresh_token' => $contentType,
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getPaymentReferenceNo()
    {
        $client = new Client([
            'base_uri' => config("line.line_pay_uri")
        ]);
        $response = $client->post('/user-referenceNo/get', [
            'headers' => $this->headers,
            'json' => $requestBody,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

}
