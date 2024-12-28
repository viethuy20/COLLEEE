<?php
namespace App\External;

use GuzzleHttp\Client;


/**
 * Description of Google
 *
 * @author t_moriizumi
 */
class Google {
    public static function getRecaptchaUse() {
        return config('google.recaptcha.USE');
    }
    public static function getRecaptchaSiteKey() {
        return config('google.recaptcha.SITE_KEY');
    }
    public static function getRecaptchaJsUrl() {
        return config('google.recaptcha.JS_URL').'?'. http_build_query(['render' => self::getRecaptchaSiteKey()]);
    }
    public static function getRecaptchaClass() {
        return config('google.recaptcha.CLASS');
    }
    public static function getRecaptchaParamKey() {
        return config('google.recaptcha.PARAM_KEY');
    }

    /**
     * リキャプチャ検証.
     * @param string|NULL $g_recaptcha_response reCAPTCHAアクションレスポンス
     * @param string $ip IP
     * @param function $func 自己評価関数
     * @return bool 成功の場合はtrueを、失敗の場合はfalseを返す
     */
    public static function checkRecaptcha($g_recaptcha_response, string $ip, $func = null) : bool {
        // リキャプチャを利用しない場合
        if (!self::getRecaptchaUse()) {
            return true;
        }

        // レスポンスが存在しない場合
        if (!isset($g_recaptcha_response) || $g_recaptcha_response == '') {
            \Log::info('Google g-recaptcha-response empty ip:'.$ip);
            return false;
        }

        $client = new Client();
        $options = ['form_params' => [
            'secret' => config('google.recaptcha.SECRET_KEY'),
            'response' => $g_recaptcha_response,
            'remoteip' => $ip]];
        // プロキシ
        if (!config('google.network.PROXY')) {
            $options['proxy'] = '';
        }
        // SSL証明書回避
        if (!config('google.network.SSL_VERIFY')) {
            $options['verify'] = false;
        }
        try {
            // リクエスト実行
            $response = $client->request('POST', config('google.recaptcha.SITEVERIFY_URL'), $options);
            // HTTPステータス確認
            if ($response->getStatusCode() != 200) {
                \Log::error('Google g-recaptcha-status error ip:'.$ip);
                return true;
            }
            $body = $response->getBody();
            // リキャプチャ確認
            $recaptcha_data = json_decode($body);
            // フラグ確認
            $res = isset($recaptcha_data->success) && $recaptcha_data->success;
            if (!$res) {
                \Log::error('Google g-recaptcha-response error ip:'.$ip);
                return true;
            }
            // 自己評価関数が存在しない場合
            if (!isset($func)) {
                return true;
            }
            // 関数で評価
            return $func($recaptcha_data);
        } catch (\Exception $e) {
            //\Log::info('Google:'.$e->getMessage());
            //\Log::info('Google:'.$e->getTraceAsString());
            return false;
        }
    }

    /**
     * 地図URL取得.
     * @param float|NULL $lat 緯度
     * @param float|NULL $long 経度
     * @param string|NULL $address 住所
     * @return string URL
     */
    public static function getMapUrl($lat, $long, $address) {
        // データが存在しなかった場合
        if (!isset($lat) && !isset($long) && !isset($address)) {
            return null;
        }

        $params = ['iwloc' => 'A'];

        $q = null;
        if (isset($address)) {
            $q = $address;
        }

        // 経度,緯度が存在した場合
        if (isset($lat) && isset($long)) {
            $loc = $lat.','.$long;
            $q = isset($q) ? $q.'@'.$loc : 'loc:'.$loc;
        }

        $params['q'] = $q;
        return config('google.map.URL').'?'. http_build_query($params);

        //$google_path = '/@'.rawurlencode($lat).','.rawurlencode($long).',17z?hl=ja';
        //$google_path = '?='.rawurlencode($address);
        //return config('google.map.URL').$google_path;
    }
}
