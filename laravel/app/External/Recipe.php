<?php
namespace App\External;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

/**
 * レシピ.
 * @author t_moriizumi
 */
class Recipe
{
    private $params = null;
    private $body = null;

    /**
     * 設定値取得.
     * @param string $key キー
     * @return mixed 設定値
     */
    private static function getConfig(string $key)
    {
        // 読み込む設定を環境によって切り替える
        return config('recipe.'.$key);
    }

    private static function getCache($cache_key, $func)
    {
        $cache = Cache::get($cache_key);
        if (isset($cache)) {
            return json_decode($cache);
        }

        $cache = $func();

        if (!isset($cache)) {
            return null;
        }

        Cache::add($cache_key, $cache, 5);

        return json_decode($cache);
    }

    /**
     * 新着取得.
     */
    public static function getNewRecipeList()
    {
        return self::getCache('RecipeNewList', function () {
            $recipe = new Recipe();
            $recipe->params = (object) ['type' => 'new'];
            $res = $recipe->execute();
            if (!$res) {
                return null;
            }

            return (string) ($recipe->getBody());
        });
    }

    /**
     * 人気取得.
     */
    public static function getPopRecipeList()
    {
        return self::getCache('RecipePopList', function () {
            $recipe = new Recipe();
            $recipe->params = (object) ['type' => 'pop'];
            $res = $recipe->execute();
            if (!$res) {
                return null;
            }
            return (string) ($recipe->getBody());
        });
    }

    /**
     * ショッピング取得.
     */
    public static function getShoppingRecipeList()
    {
        return self::getCache('RecipeShoppingList', function () {
            $recipe = new Recipe();
            $recipe->params = (object) ['type' => 'shopping'];
            $res = $recipe->execute();
            if (!$res) {
                return null;
            }
            return (string) ($recipe->getBody());
        });
    }

    /**
     * キーワード検索取得.
     * @param string $keyword キーワード
     */
    public static function getRecipeListFromKeyword(string $keyword)
    {
        $recipe = new self();
        $recipe->params = (object) ['type' => 'keyword', 'text' => $keyword];
        $res = $recipe->execute();
        if (!$res) {
            return null;
        }
        return json_decode((string) ($recipe->getBody()));
    }

    /**
     * タグ検索取得.
     * @param string $tag タグ
     */
    public static function getRecipeListFromTag(string $tag)
    {
        $recipe = new self();
        $recipe->params = (object) ['type' => 'tag', 'text' => $tag];
        $res = $recipe->execute();
        if (!$res) {
            return null;
        }
        return json_decode((string) ($recipe->getBody()));
    }

    /**
     * カテゴリ検索取得.
     * @param string $category カテゴリ
     */
    public static function getRecipeListFromCategory(string $category)
    {
        $recipe = new self();
        $recipe->params = (object) ['type' => 'category', 'text' => $category];
        $res = $recipe->execute();
        if (!$res) {
            return null;
        }
        return json_decode((string) ($recipe->getBody()));
    }

    /**
     * ID検索取得.
     * @param array $id_list IDリスト
     */
    public static function getRecipeListFromId(array $id_list)
    {
        $recipe = new self();
        $recipe->params = (object) ['type' => 'ids', 'target' => implode(',', $id_list)];
        $res = $recipe->execute();
        if (!$res) {
            return null;
        }

        $recipe_data = json_decode((string) ($recipe->getBody()));
        // ステータス確認
        if (!$recipe_data->result->status) {
            return null;
        }

        $recipe_list = [];

        if (!empty($recipe_data->items)) {
            // ID順でソート
            $recipe_item_list = $recipe_data->items;
            $recipe_map = [];
            foreach ($recipe_item_list as $recipe) {
                $recipe_map[$recipe->id] = $recipe;
            }

            foreach ($id_list as $recipe_id) {
                if (!isset($recipe_map[$recipe_id])) {
                    continue;
                }
                $recipe_list[] = $recipe_map[$recipe_id];
            }
        }

        $recipe_data->items = $recipe_list;

        return $recipe_data;
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

        $options = [
            'http_errors' => false,
            'timeout' => 30];
        // クエリ作成
        if (isset($this->params)) {
            $options['query'] = http_build_query($this->params);
        }

        // プロキシ
        if (!$this->getConfig('PROXY')) {
            $options['proxy'] = '';
        }
        // SSL証明書回避
        if (!$this->getConfig('SSL_VERIFY')) {
            $options['verify'] = false;
        }

        try {
            // リクエスト実行
            $response = $client->request('GET', $url, $options);

            // HTTPステータス確認
            $status = $response->getStatusCode();
            if ($status != 200) {
                return false;
            }
            $this->body = $response->getBody();
        } catch (\Exception $e) {
            \Log::info('Recipe:'.$e->getMessage());
            return false;
        }
        return true;
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
