<?php
namespace App\External;

use GuzzleHttp\Client;

/**
 * dポイント.
 * @author y_saito
 */
class DPoint
{
    private $error_code = null;
    private $body = null;
    private $response = null;

    /**
     * 設定値取得.
     * @param string $key キー
     * @return mixed 設定値
     */
    private static function getConfig(string $key)
    {
        // 読み込む設定を環境によって切り替える
        return config('d_point.'.$key);
    }

    /**
     * 認証URL取得.
     * 
     * @param string $user_id ユーザーID
     * @param string $user_name ユーザー名
     * @param string|NULL $state コールバック値
     * @param string|NULL $product_id 対象となる商品の識別子
     * @return string 認証URL
     */
    public static function getAuthUrl($state = null, $product_id = null, $nonce = null) : string
    {
        // パラメーターを作成
        if (isset($state)) {
            $params['status'] = $state;
        }
        if (isset($nonce)) {
            $param['nonce'] = $nonce;
        }
        // URLを作成して返す
        return self::getConfig('AuthURL').'/exchange/authorize'.'?'. http_build_query($params);
    }

    public static function getSignedURL(string $url, array $params)
    {
        $params = $params ?? [];
        // URLを解析
        $parsed_url = parse_url($url);

        $base_url = '';
        if (isset($parsed_url['scheme'])) {
            $base_url = $base_url.$parsed_url['scheme'].'://';
        }
        if (isset($parsed_url['user'])) {
            $base_url = $base_url.$parsed_url['user'];
            if (isset($parsed_url['pass'])) {
                $base_url = $base_url.':'.$parsed_url['pass'];
            }
            $base_url = $base_url.'@';
        }
        if (isset($parsed_url['host'])) {
            $base_url = $base_url.$parsed_url['host'];
        }
        if (isset($parsed_url['port'])) {
            $base_url = $base_url.':'.$parsed_url['port'];
        }
        if (isset($parsed_url['path'])) {
            $base_url = $base_url.$parsed_url['path'];
        }
        if (isset($parsed_url['query'])) {
            $p = [];
            parse_str($parsed_url['query'], $p);
            $params = array_merge($params, $p);
        }

        // URLを作成して返す
        return $base_url.'?'. http_build_query($params) . (isset($parsed_url['fragment']) ?
            '#'.$parsed_url['fragment'] : '');
    }

    /**
     * コンテンツ認証URL取得.
     * @param string $user_id ユーザーID
     * @param string $user_name ユーザー名
     * @param string $state コールバック値
     * @param string $product_id 対象となる商品の識別子
     * @return string コンテンツ認証URL
     */
    public static function getContentAuthUrl(
        $state = null,
        $nonce = null
    ) : string {
        // パラメーターを作成
        $params = [];
        $params['client_id'] = self::getConfig('CLIENT_ID');
        $params['state'] = $state;
        $params['scope'] = 'openid dpoint_number';
        $params['response_type'] = 'code';
        $params['redirect_uri'] = self::getConfig('REDIRECT_URI');
        $params['nonce'] = $nonce;
        $params['authif'] = '1';    //ドコモ回線ユーザー以外もログイン可能

        return self::getSignedURL(self::getConfig('AuthURL'), $params);
    }

    /**
     * 実行.
     * 
     */
    public function execute($params) 
    {
        $client = new Client();
        $method = 'POST';
        // URL取得
        $url = self::getConfig('API2_2_URL');

        // 認証キー作成
        $authorization = 'Basic '. base64_encode(self::getConfig('CLIENT_ID'). ':'. self::getConfig('CLIENT_SECRET'));

        $options = [
            'http_errors' => false,
            'headers' => ['Authorization' => $authorization, 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF8', 'Content-Length' => '1', 'Host' => 'conf.uw.docomo.ne.jp'],
            'timeout' => 60,
        ];

        
        $options['form_params'] = $params;
        $options['version'] = '1.1';

        // プロキシ
        if (!self::getConfig('PROXY')) {
            $options['proxy'] = '';
        }

        // SSL証明書回避
        if (!self::getConfig('SSL_VERIFY')) {
            $options['verify'] = false;
        }

        try {
            // リクエスト実行
            $response = $client->request($method, $url, $options);

            // HTTPステータス確認
            $status = $response->getStatusCode();

            $this->body = $response->getBody();

            if (isset($this->body) && $this->body != '') {
                $this->response = json_decode($this->body);
                $access_token = $this->response->access_token;
                $id_token = $this->response->id_token;
                $ex_id_token = explode('.', $id_token);
                //20230302 URLセーフ対応
                //$id_token_analysis = base64_decode($ex_id_token[1]);
                $base64_decode_text = str_replace(array('-', '_'), array('+', '/'), $ex_id_token[1]);
                $id_token_analysis = base64_decode($base64_decode_text);
                $id_token_obj = json_decode($id_token_analysis);
                $nonce = session()->get('nonce');
                if ($nonce != $id_token_obj->nonce) {
                    session()->forget('nonce');
                    return false;
                }
            }
            if ($status != 200) {
                if (isset($this->response->code)) {
                    $this->error_code = $this->response->code;
                }
                return false;
            }

        } catch (\Exception $e) {
            \Log::info('D Point:'.$e->getMessage());
            return false;
        }

        // URL取得
        $url = self::getConfig('API2_3_URL');

        // 認証キー作成
        $authorization = 'Bearer '. $access_token;

        $options = [
            'http_errors' => false,
            'headers' => ['Authorization' => $authorization, 'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF8', 'Host' => 'conf.uw.docomo.ne.jp'],
            'timeout' => 60,
            'version' => '1.1'
        ];
        $method = 'GET';

        try {
            // リクエスト実行
            $response = $client->request($method, $url, $options);

            // HTTPステータス確認
            $status = $response->getStatusCode();
            $this->body = $response->getBody();

            if (isset($this->body) && $this->body != '') {
                $this->response = json_decode($this->body);
                if ($this->response->d_pt_result == 'OK') {
                    return ['sub' => $this->response->sub, 'd_pt_number' => $this->response->d_pt_number];
                }
            }
            if ($status != 200) {
                if (isset($this->response->code)) {
                    $this->error_code = $this->response->code;
                }
                return false;
            }
        } catch (\Exception $e) {
            \Log::info('D Point:'.$e->getMessage());
            return false;
        }
        return false;
    }

    /**
     * エラーコード取得.
     * @return string エラーコード
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * 結果取得.
     * @return string 結果
     */
    public function getBody()
    {
        return $this->body;
    }
}
