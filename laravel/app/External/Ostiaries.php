<?php
namespace App\External;

use GuzzleHttp\Client;

/**
 * 着信認証.
 */
class Ostiaries {
    /**
     * 設定値取得.
     * @param string $key キー
     * @return mixed 設定値
     */
    public static function getConfig(string $key) {
        // 読み込む設定を環境によって切り替える
        return config('ostiaries.'.$key);
    }

    /**
     * HTTP実行.
     * @param string $path 認証コード
     * @param array $params 追加リクエスト
     * @return string|NULL 結果
     */
    private static function execute(string $path, array $params) {
        $p = $params;
        $p['service_id'] = self::getConfig('service_id');
        $p['access_key'] = self::getConfig('access_key');
        $client = new Client();
        $url = self::getConfig('URL').$path;
        $options = ['json' => $p, 'headers' => ['Content-Type' => 'application/json; charset=UTF-8']];
        // プロキシ
        if (!self::getConfig('PROXY')) {
            $options['proxy'] = '';
        }
        // SSL証明書回避
        if (!self::getConfig('SSL_VERIFY')) {
            $options['verify'] = false;
        }
        try {
            $response = $client->request('POST', $url, $options);
            // HTTPステータスを確認
            return $response->getBody();
        } catch (\Exception $e) {
            \Log::error('Ostiaries:'.$e->getTraceAsString());
            return null;
        }
    }

    /**
     * NewTransaction.
     * @param string $cp_id CP識別子
     * @param string $tel 電話番号
     * @param array $answerbacks 返答
     * @return string|NULL 結果
     */
    public static function getNewTransaction(string $cp_id, string $tel, array $answerbacks = []) {
        $p_answerbacks = isset($answerbacks['method']) ? $answerbacks : ['method' => 'busy'];
        $params = ['customer_numbers' => [$tel], 'identifier' => $cp_id, 'answerbacks' => [$p_answerbacks]];
        return self::execute('/NewTransaction', $params);
    }

    /**
     * StartAuthentication.
     * @param string $transaction_id トランザクションUUID
     * @return string|NULL 結果
     */
    public static function getStartAuthentication(string $transaction_id) {
        return self::execute('/StartAuthentication', ['transaction_id' => $transaction_id]);
    }

    /**
     * GetTransactionStatus.
     * @param string $transaction_id トランザクションUUID
     * @return string|NULL 結果
     */
    public static function getGetTransactionStatus(string $transaction_id) {
        return self::execute('/GetTransactionStatus', ['transaction_id' => $transaction_id]);
    }
}
