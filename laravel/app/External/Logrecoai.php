<?php
namespace App\External;

use DB;
use Auth;
use App\Program;
use GuzzleHttp\Client;
use WrapPhp;

/**
 * Description of Logrecoai
 *
 * @author k_miyashita
 */
class Logrecoai
{
    const IS_CHECK_MODE = false;

    private $api_base = 'https://api.logreco1.jp/nikko_point';
    private $spot_name_list = [
        'トップページ_広告レコメンド',
        'プログラム詳細_広告レコメンド',
        'ポイ活お得情報_トップ_おすすめの記事',
        'ポイ活お得情報_トップ_おすすめの広告',
        'ポイ活お得情報_トップ_人気の記事',
        'ポイ活お得情報_記事詳細_おすすめの記事',
        'ポイ活お得情報_記事詳細_おすすめの広告',
        'ポイ活お得情報_記事詳細_人気の記事',
        'ポイ活お得情報_記事一覧_おすすめの記事',
        'ポイ活お得情報_記事一覧_おすすめの広告',
        'ポイ活お得情報_記事一覧_人気の記事',
    ];

    public static function isRequestToSend(): bool {
        if ((config('app.env') === 'production') || (self::IS_CHECK_MODE === true)) {
            return true;
        }
        return false;
    }

    public static function getSessionId(): string {
        $logrecoai_session_id = '';
        $session_config = config('session');
        $session_cookie_name = $session_config['cookie'];
        $session_id = request()->cookie($session_cookie_name) ?? '';
        if (is_string($session_id)) {
            $logrecoai_session_id = md5($session_id);
        }
        return $logrecoai_session_id;
    }

    public static function getUserId(): string {
        $logrecoai_user_id = '';
        if (Auth::check()) {
            $user = Auth::user();
        }
        if (isset($user)) {
            $logrecoai_user_id = (string)$user->id;
        }
        return $logrecoai_user_id;
    }

    public function historyView(string $session_id = '', string $user_id = '', string $item_id = ''): bool {
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        if (!empty($item_id)) {
            $params['item_id'] = $item_id;
        }

        if (!isset($params['session_id']) || !isset($params['item_id']) || !preg_match('/^(pg\d+|wp\d+)$/', $params['item_id'])) {
            return false;
        }

        $endpoint = $this->api_base . '/json/action_log/history/view';
        $url = $endpoint . '?' . http_build_query($params);

        if (self::IS_CHECK_MODE === true) {
            \Log::info(__LINE__ . ':' . $url);
        }

        $client = new Client();
        $options = ['timeout' => 20];
        try {
            $response = $client->request('GET', $url, $options);
            $status = $response->getStatusCode();
            if ($status === 200) {
                $body = $response->getBody();
                $body_decoded = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $message_ids = $body_decoded['message_ids'] ?? [];
                    if (in_array(0, $message_ids)) { // 0:正常
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    public function kpiClick(string $session_id = '', string $user_id = '', string $item_id = '', string $type_name, string $spot_name): bool {
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        if (!empty($item_id)) {
            $params['item_id'] = $item_id;
        }
        if (!empty($type_name)) {
            $params['type_name'] = $type_name;
        }
        if (!empty($spot_name)) {
            $params['spot_name'] = $spot_name;
        }

        if (!isset($params['session_id']) || !isset($params['item_id']) || !preg_match('/^(pg\d+|wp\d+)$/', $params['item_id']) || !isset($params['type_name'])) {
            return false;
        }

        $endpoint = $this->api_base . '/json/action_log/kpi/click';
        $url = $endpoint . '?' . http_build_query($params);

        if (self::IS_CHECK_MODE === true) {
            \Log::info(__LINE__ . ':' . $url);
        }

        $client = new Client();
        $options = ['timeout' => 20];
        try {
            $response = $client->request('GET', $url, $options);
            $status = $response->getStatusCode();
            if ($status === 200) {
                $body = $response->getBody();
                $body_decoded = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $message_ids = $body_decoded['message_ids'] ?? [];
                    if (in_array(0, $message_ids)) { // 0:正常
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    public function getArticlesRecommendHybrid(string $session_id = '', string $user_id = '', string $item_ids = '', int $num = 6, string $spot_name = ''): array {
        $params = [
            'category1' => 'wordpress',
            'category2' => 'PC iOS Android',
            'hybrid_type' => '1',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        if (!empty($item_ids)) {
            $params['item_ids'] = $item_ids;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ai/recommend/hybrid';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getArticles($url);

        return $recommend_data;
    }

    public function getArticlesRecommendContentBase(string $session_id = '', string $user_id = '', string $item_ids = '', int $num = 6, string $spot_name = ''): array {
        $params = [
            'category1' => 'wordpress',
            'category2' => 'PC iOS Android',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        if (!empty($item_ids)) {
            $params['item_ids'] = $item_ids;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ai/recommend/content_base';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getArticles($url);

        return $recommend_data;
    }

    public function getArticlesRankingView(string $session_id = '', int $num = 6, string $spot_name = ''): array {
        $params = [
            'category1' => 'wordpress',
            'category2' => 'PC iOS Android',
            'method_type' => '1',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ranking/view';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getArticles($url);

        return $recommend_data;
    }

    private function getArticles(string $url): array {
        if (self::IS_CHECK_MODE === true) {
            \Log::info(__LINE__ . ':' . $url);
        }
        $recommend_data = [];

        $client = new Client();
        $options = ['timeout' => 20];
        try {
            $response = $client->request('GET', $url, $options);
            $status = $response->getStatusCode();
            if ($status === 200) {
                $body = $response->getBody();
                $response_arr = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $message_ids = $response_arr['message_ids'] ?? [];
                    if (in_array(0, $message_ids)) { // 0:正常
                        foreach ($response_arr['items'] as $items) {
                            $article_id = str_replace('wp', '', $items['item_id']);
                            // 必要なデータを取得して揃える
                            $recommend_data[] = [
                                'item_id' => $items['item_id'],
                                'article_id' => $article_id,
                                'date' => $items['date'],
                                'image_url' => $this->get_article_image_url($article_id),
                                'title' => $items['column4'],
                                'url' => $this->get_article_url($article_id),
                                'type_name' => $items['type_name'],
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $recommend_data = [];
        }

        return $recommend_data;
    }

    public function getProgramsRecommendCollaborativeFiltering(string $session_id = '', string $user_id = '', string $item_ids = '', int $num = 6, string $device = 'pc', string $spot_name = ''): array {
        $params = [
            'category1' => 'program',
            'compensation' => '1',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        // // item_id は今回は渡さない仕様
        // if (!empty($item_ids)) {
        //     $params['item_ids'] = $item_ids;
        // }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        // デバイス pc のときはスマホ専用広告を出さない（category2 を PC iOS Android に制限）
        // デバイス sp のときはスマホ専用広告も出してよいため制限なし
        if (!empty($device) && $device === 'pc') {
            $params['category2'] = 'PC iOS Android';
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ai/recommend/collaborative_filtering';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getPrograms($url);

        return $recommend_data;
    }

    public function getProgramsRecommendHybrid(string $session_id = '', string $user_id = '', string $item_ids = '', int $num = 6, string $device = 'pc', string $spot_name = ''): array {
        $params = [
            'category1' => 'program',
            'hybrid_type' => '1',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
            $params['hybrid_type'] = '2';
        }
        if (!empty($item_ids)) {
            $params['item_ids'] = $item_ids;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        // デバイス pc のときはスマホ専用広告を出さない（category2 を PC iOS Android に制限）
        // デバイス sp のときはスマホ専用広告も出してよいため制限なし
        if (!empty($device) && $device === 'pc') {
            $params['category2'] = 'PC iOS Android';
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ai/recommend/hybrid';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getPrograms($url);

        return $recommend_data;
    }

    public function getProgramsRecommendContentBase(string $session_id = '', string $user_id = '', string $item_ids = '', int $num = 6, string $device = 'pc', string $spot_name = ''): array {
        $params = [
            'category1' => 'program',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (!empty($user_id)) {
            $params['user_id'] = $user_id;
        }
        if (!empty($item_ids)) {
            $params['item_ids'] = $item_ids;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        // デバイス pc のときはスマホ専用広告を出さない（category2 を PC iOS Android に制限）
        // デバイス sp のときはスマホ専用広告も出してよいため制限なし
        if (!empty($device) && $device === 'pc') {
            $params['category2'] = 'PC iOS Android';
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ai/recommend/content_base';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getPrograms($url);

        return $recommend_data;
    }

    public function getProgramsRankingView(string $session_id = '', int $num = 6, string $device = 'pc', string $spot_name = ''): array {
        $params = [
            'category1' => 'program',
            'method_type' => '1',
        ];
        if (!empty($session_id)) {
            $params['session_id'] = $session_id;
        }
        if (is_numeric($num)) {
            $params['response_number'] = $num;
        }
        // デバイス pc のときはスマホ専用広告を出さない（category2 を PC iOS Android に制限）
        // デバイス sp のときはスマホ専用広告も出してよいため制限なし
        if (!empty($device) && $device === 'pc') {
            $params['category2'] = 'PC iOS Android';
        }
        if (!empty($spot_name) && in_array($spot_name, $this->spot_name_list)) {
            $params['spot_name'] = $spot_name;
        }

        $endpoint = $this->api_base . '/json/ranking/view';
        $url = $endpoint . '?' . http_build_query($params);

        $recommend_data = $this->getPrograms($url);

        return $recommend_data;
    }

    private function getPrograms(string $url): array {
        if (self::IS_CHECK_MODE === true) {
            \Log::info(__LINE__ . ':' . $url);
        }
        $recommend_data = [];

        $client = new Client();
        $options = ['timeout' => 20];
        try {
            $response = $client->request('GET', $url, $options);
            $status = $response->getStatusCode();
            if ($status === 200) {
                $body = $response->getBody();
                $response_arr = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $message_ids = $response_arr['message_ids'] ?? [];
                    if (in_array(0, $message_ids)) { // 0:正常
                        foreach ($response_arr['items'] as $items) {
                            $program_id = str_replace('pg', '', $items['item_id']);
                            // 必要なデータを取得して揃える
                            $program_data = $this->get_program_data($program_id);
                            if (!is_null($program_data)) {
                                $recommend_data[] = [
                                    'item_id' => $items['item_id'],
                                    'program_id' => $program_id,
                                    'date' => $items['date'],
                                    'title' => $items['column4'],
                                    'url' => $this->get_program_url($program_id),
                                    'type_name' => $items['type_name'],
                                    'program_data' => $program_data,
                                ];
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $recommend_data = [];
        }

        return $recommend_data;
    }

    private function get_article_image_url($article_id) {
        $base_url = config('app.url');

        $sql = <<<EOF
SELECT
    ID,
    guid as uri
FROM
    wp_posts
WHERE
    ID in (
        SELECT
            meta_value
        FROM
            wp_postmeta
        WHERE
            post_id = {$article_id}
            AND meta_key = '_thumbnail_id'
    )
UNION
SELECT
    post_id as ID,
    concat(
        '{$base_url}',
        '/article/wp-content/uploads/',
        meta_value
    ) as uri
FROM
    wp_postmeta
WHERE
    meta_key = '_wp_attached_file'
    AND post_id = {$article_id}
;
EOF;
        try {
            $articles = DB::connection('mysql2')->select($sql);
            if (WrapPhp::count($articles) == 1) {
                $image_url = $articles[0]->uri;
                if ($image_url) {
                    return $image_url;
                }
            }
        } catch (\Exception $e) {
            return $this->get_noimage_url();
        }
        return $this->get_noimage_url();
    }

    private function get_noimage_url() {
        return '/article/wp-content/themes/article/assets_new/img/article/noimage.jpg';
    }

    private function get_article_url($article_id) {
        return '/article/?p=' . $article_id;
    }

    private function get_program_url($program_id) {
        return '/programs/' . $program_id;
    }

    private function get_program_data($program_id) {
        $program_data = Program::find($program_id);
        return $program_data;
    }
}
