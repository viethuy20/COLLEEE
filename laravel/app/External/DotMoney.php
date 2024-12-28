<?php
namespace App\External;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

/**
 * ドットマネー.
 * @author t_moriizumi
 */
class DotMoney
{
    private $path = null;
    private $params = null;
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
        return config('dot_money.'.$key);
    }

    /**
     * 署名取得.
     * @param array $data データ
     * @return string 署名
     */
    private static function getSignature(array $data) : string
    {
        // 署名作成
        return hash_hmac('sha256', hash('sha256', implode("\n", $data), false), self::getConfig('HashKey'));
    }

    /**
     * 口座番号.
     * @param bool $use_content_auth コンテンツ認証
     * @param string $account_number 口座番号
     * @return string 口座番号.
     */
    private static function getAccountNumber(bool $use_content_auth, string $account_number) : string
    {
        return $use_content_auth ? 'exid-'.$account_number : $account_number;
    }

    public static function isDotMoneyUrl(string $url) : bool
    {
        return Str::startsWith($url, self::getConfig('AuthURL'));
    }

    /**
     * 認証URL取得.
     * @param string $user_id ユーザーID
     * @param string $user_name ユーザー名
     * @param string|NULL $state コールバック値
     * @param string|NULL $product_id 対象となる商品の識別子
     * @return string 認証URL
     */
    public static function getAuthUrl($state = null, $product_id = null) : string
    {
        // パラメーターを作成
        $params = ['access_key' => self::getConfig('AccessKey')];
        if (isset($state)) {
            $params['status'] = $state;
        }
        if (isset($product_id)) {
            $params['product_id'] = $product_id;
        }
        // URLを作成して返す
        return self::getConfig('AuthURL').'/exchange/authorize'.'?'. http_build_query($params);
    }

    public static function getSignedURL(string $url, string $user_id, $params = null)
    {
        $params = $params ?? [];
        $params['user_id'] = $user_id;
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

        // パラメーターを追加
        $params['access_key'] = self::getConfig('AccessKey');
        $params['access_date'] = time();
        // 署名
        $params['signature'] = self::getSignature([$params['access_date'], $params['product_id'] ?? '',
            $params['user_id'], $params['user_name'] ?? '', $params['status'] ?? '']);

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
        string $user_id,
        $user_name = null,
        $state = null,
        $product_id = null
    ) : string {
        // パラメーターを作成
        $params = [];
        if (isset($user_name)) {
            $params['user_name'] = $user_name;
        }
        if (isset($state)) {
            $params['status'] = $state;
        }
        if (isset($product_id)) {
            $params['product_id'] = $product_id;
        }
        return self::getSignedURL(self::getConfig('AuthURL').'/exchange/authorize/external', $user_id, $params);
    }

    /**
     * コンテンツ認証方式ドットマネー設定URL取得.
     * @param string $user_id ユーザーID
     * @param string $user_name ユーザー名
     * @param string $product_id 対象となる商品の識別子
     * @return string コンテンツ認証方式ドットマネーログインURL
     */
    public static function getContentAuthDotmoneySettingUrl(
        string $user_id,
        $user_name = null,
        $product_id = null
    ) : string {
        // パラメーターを作成
        $params = [];
        if (isset($user_name)) {
            $params['user_name'] = $user_name;
        }
        if (isset($product_id)) {
            $params['product_id'] = $product_id;
        }
        return self::getSignedURL(self::getConfig('AuthURL').'/setting', $user_id, $params);
    }

    /**
     * 口座情報確認オブジェクト取得.
     * @param bool $use_content_auth コンテンツ認証
     * @param string $account_number 口座番号
     * @return DotMoney ドットマネーオブジェクト
     */
    public static function getShow(bool $use_content_auth, string $account_number) : DotMoney
    {
        $dot_money = new self();
        $dot_money->path = sprintf("/account/%s", self::getAccountNumber($use_content_auth, $account_number));
        return $dot_money;
    }

    /**
     * 実行.
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public function execute() : bool
    {
        $client = new Client();

        // URL取得
        $url = self::getConfig('URL');

        $request_date = time();

        // メソッドとクエリ作成
        if (isset($this->params)) {
            $method = 'POST';
            $query = json_encode($this->params);
        } else {
            $method = 'GET';
            $query = '';
        }

        // 認証キー作成
        $authorization = implode('_', [
            self::getConfig('Version'),
            self::getConfig('AccessKey'),
            $request_date,
            self::getSignature([$request_date, $method, $this->path, '', $query])]);

        $options = [
            'http_errors' => false,
            'headers' => ['Authorization' => $authorization, 'Content-Type' => 'application/json'],
            'timeout' => 60,
            'body' => $query];
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
            $response = $client->request($method, $url.$this->path, $options);

            // HTTPステータス確認
            $status = $response->getStatusCode();
            $this->body = $response->getBody();
            if (isset($this->body) && $this->body != '') {
                $this->response = json_decode($this->body);
            }
            if ($status != 200) {
                if (isset($this->response->code)) {
                    $this->error_code = $this->response->code;
                }
                return false;
            }
        } catch (\Exception $e) {
            \Log::info('DotMoney:'.$e->getMessage());
            return false;
        }
        return true;
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

    /**
     * レスポンス取得.
     */
    public function getResponse()
    {
        return $this->response;
    }
}
