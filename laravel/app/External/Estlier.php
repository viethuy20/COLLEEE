<?php
namespace App\External;

use Carbon\Carbon;
use GuzzleHttp\Client;

use App\User;

/**
 * Description of Estlier
 *
 * @author t_moriizumi
 */
class Estlier
{
    private static $GANRE_MAP = ['column' => 1, 'photo' => 6, 'observation' => 26,
        'animal' => 18, 'map' => 34, 'cooking' => 36, 'comic' => 16, 'hirameki' => 54,
        'mix' => 58];

    /**
     * 設定値取得.
     * @param string $key キー
     * @return mixed 設定値
     */
    public static function getConfig(string $key)
    {
        return config('estlier.'.$key);
    }

    public static function getGanreNumber(string $ganre)
    {
        return self::$GANRE_MAP[$ganre] ?? null;
    }

    /**
     * アンケートリストを取得.
     * @param string|NULL $user_name ユーザー名
     * @return array|NULL 成功の場合はアンケートリストを,失敗の場合はNULLを返す
     */
    public static function getEnqueteList($user_name = null)
    {
        // ユーザー名がない場合は、システムユーザー名を利用する
        $muid = $user_name ?? User::getNameById(config('app.system_user_id'));

        $url = self::getConfig('URL').'/get_enquete_api.php?'.http_build_query(['muid' => $muid]);

        $ganre_label_map = self::getConfig('ganre_label_map');

        $client = new Client();

        $options = ['timeout' => 20];
        // プロキシ
        if (!self::getConfig('PROXY')) {
            $options['proxy'] = '';
        }
        // SSL証明書回避
        if (!self::getConfig('SSL_VERIFY')) {
            $options['verify'] = false;
        }

        $now = Carbon::now();

        try {
            // リクエスト実行
            $response = $client->request('GET', $url, $options);

            // HTTPステータス確認
            $status = $response->getStatusCode();

            // 失敗した場合
            if ($status != 200) {
                return null;
            }
            $body = $response->getBody();

            // デコード失敗
            $p_enq_question_list_list = json_decode($body);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            // 整形
            $enq_question_list = [];
            foreach ($p_enq_question_list_list as $p_enq_question_list) {
                foreach ($p_enq_question_list as $p_enq_question) {
                    $enq_date = Carbon::createFromFormat('Ymd', $p_enq_question->enq_date)
                            ->startOfDay();
                    $expire_at = $enq_date->copy()
                            ->endOfDay()
                            ->addDays(7);

                    // 回答期限を過ぎている場合
                    if (config('app.env') == 'production' && $expire_at->lt($now)) {
                        continue;
                    }

                    // システムユーザーで検索した場合は、回答状況をリセットにする
                    $p_enq_question->already = isset($user_name) ? $p_enq_question->already : 0;
                    // 回答期限追加
                    $p_enq_question->expire_at = $expire_at;
                    // ジャンル
                    $p_enq_question->ganre_label = $ganre_label_map[$p_enq_question->ganre] ?? '';
                    $enq_question_list[] = $p_enq_question;
                }
            }

            usort($enq_question_list, function ($a, $b) {
                if ($a->expire_at->eq($b->expire_at)) {
                    return 0;
                }
                return $a->expire_at->lt($b->expire_at) ? 1 : -1;
            });

            return $enq_question_list;
        } catch (\Exception $e) {
            \Log::info('Estlier:'.$e->getMessage());
            return null;
        }
    }

    /**
     * アンケートURL取得.
     * @param string $user_name ユーザー名
     * @param string $ganre アンケート種別
     * @param string $enq_date 出題日
     * @return string URL
     */
    public static function getEnqueteUrl(string $user_name, string $ganre, string $enq_date) : string
    {
        return self::getConfig('URL').'/enquete/'.rawurlencode($ganre).'/rule.php?'.
            http_build_query(['muid' => $user_name, 'date' => $enq_date]);
    }
}
