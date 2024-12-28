<?php
namespace App\Search;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use WrapPhp;

require_once APP_CLASS_PATH . 'ROI/Fancrew/ShopService.php';

/**
 * Fancrew検索条件.
 */
class FancrewCondition
{
    const CATEGORY_CACHE_KEY = 'category_map';
    const PREFECTURE_CACHE_KEY = 'prefecture_map';
        
    /**
     *パラメーター.
     */
    protected $params = [];

    protected $key_list;

    public function __construct() {
        //parent::__construct();
        $this->key_list = ['page', 'sort', 'limit', 'category_id', 'prefecture_id', 'area_id', 'freeword'];
        $this->params['page'] = 1;
        $this->params['sort'] = 1;
        $this->params['limit'] = 10;	// 検索一覧上限件数10
        $this->params['category_id'] = 0;
        $this->params['prefecture_id'] = 0;
        $this->params['area_id'] = 0;
    }

    public function setParams(array $params) {
        foreach ($params as $key => $param) {
            if (in_array($key, $this->key_list)) {
                $this->params[$key] = $param;
            }
        }
    }

    public function getParam(string $key) {
        if (!in_array($key, $this->key_list) || !isset($this->params[$key])) {
            return null;
        }
        return $this->params[$key];
    }

    /**
     * リストURL取得.
     * @param object|NULL $ext 更新
     * @return string URL
     */
    public function getListUrl($ext = null) : string
    {
        return self::getStaticListUrl($ext, $this);
    }

    /**
     * リストURL取得.
     * @param object|NULL $ext 更新
     * @param ProgramCondition|NULL $default デフォルト値
     * @return string URL
     */
    public static function getStaticListUrl($ext = null, $default = null) : string
    {
        /** 空の場合. */
        if (!isset($default)) {
            $default = new self();
        }
        $get_params = ['_pf' => 'search', '_pf_action' => 'search'];
        // ページ
        $page = $ext->page ?? $default->getParam('page');
        if ($page != 1) {
            $get_params['page'] = $page;
        }
        // 並び順
        $sort = $ext->sort ?? $default->getParam('sort');
        if (isset($get_params['sort']) || $sort != 1) {
            $get_params['sort'] = $sort;
        }
        // フリーワード検索
        $freeword = $ext->freeword ?? $default->getParam('freeword');
        if (isset($freeword)) {
            $get_params['freeword'] = $freeword;
        }
        // カテゴリ検索
        $category_id = $ext->category_id ?? $default->getParam('category_id');
        if (isset($category_id) && $category_id > 0) {
            $get_params['category_id'] = $category_id;
        }
        // 都道府県検索
        $prefecture_id = $ext->prefecture_id ?? $default->getParam('prefecture_id');
        if (isset($prefecture_id) && $prefecture_id > 0) {
            $get_params['prefecture_id'] = $prefecture_id;
        }
        // エリア検索
        $area_id = $ext->area_id ?? $default->getParam('area_id');
        if (isset($area_id) && $area_id > 0) {
            $get_params['area_id'] = $area_id;
        }

        $url = route('fancrew.pages', ['action' => 'pages']);

        return empty($get_params) ? $url : $url.'?'.(http_build_query($get_params));
    }
    
    /**
     * カテゴリマスタ取得.
     * @return Collection カテゴリマスタ
     */
    public static function getCategoryMap() : Collection
    {
        // カテゴリを取得
        $category_map = Cache::get(self::CATEGORY_CACHE_KEY);
        
        // キャッシュが存在した場合
        if (isset($category_map)) {
            return $category_map;
        }
        
        $category_map = collect([['id' => '0', 'name' => '全て', 'genre_map' => ['0' => '指定なし']]]);
        /*
        $category_map->push(['id' => '1', 'name' => 'グルメ', 'genre_map' => ['0' => '指定なし']]);
        $category_map->push(['id' => '2', 'name' => '美容・健康', 'genre_map' => ['0' => '指定なし']]);
        $category_map->push(['id' => '3', 'name' => 'お買い物', 'genre_map' => ['0' => '指定なし']]);
        $category_map->push(['id' => '4', 'name' => 'その他', 'genre_map' => ['0' => '指定なし']]);
        */
        
        // カテゴリ取得
        $category_xml = \ROI_Fancrew_ShopService::get()->getGenresReal();
        if (isset($category_xml)) {
            $category_list_xml = $category_xml->Category;
            $ctotal = WrapPhp::count($category_list_xml);
            
            for ($i = 0; $i < $ctotal; $i++) {
                $c_xml = $category_list_xml[$i];
                $genre_xml = $c_xml->Genres;
                $genre_map = ['0' => '指定なし'];
                $genre_list_xml = $genre_xml->Genre;
                $gtotal = WrapPhp::count($genre_list_xml);
                for ($j = 0; $j < $gtotal; $j++) {
                    $g_xml = $genre_list_xml[$j];
                    $area_map[(int) $g_xml['id']] = (string) $g_xml['name'];
                }
                $category_map->push(['id' => (int) $c_xml['id'], 'name' => (string) $c_xml['name'], 'genre_map' => $genre_map]);
            }
            
            Cache::put(self::CATEGORY_CACHE_KEY, $category_map, 60);
        }

        return $category_map;
    }
    
    /**
     * 都道府県マスタ取得.
     * @return Collection 都道府県マスタ
     */
    public static function getPrefectureMap() : Collection
    {
        // 都道府県を取得
        $prefecture_map = Cache::get(self::PREFECTURE_CACHE_KEY);
        
        // キャッシュが存在した場合
        if (isset($prefecture_map)) {
            return $prefecture_map;
        }
        
        $prefecture_map = collect();
        /*
        $prefecture_map->push(['id' => '0', 'name' => '全国']);
        $prefecture_map->push(['id' => '1', 'name' => 'テスト', 'area_map' => ['0' => '指定なし', '1' => 'お']]);
        */
        
        // 都道府県取得
        $prefecture_xml = \ROI_Fancrew_ShopService::get()->getPrefectures();
        if (isset($prefecture_xml)) {
            $prefecture_list_xml = $prefecture_xml->Prefecture;
            $ptotal = WrapPhp::count($prefecture_list_xml);
            for ($i = 0; $i < $ptotal; $i++) {
                $p_xml = $prefecture_list_xml[$i];
                $area_xml = $p_xml->Areas;
                $area_map = ['0' => '指定なし'];
                $area_list_xml = $area_xml->Area;
                $atotal = WrapPhp::count($area_list_xml);
                for ($j = 0; $j < $atotal; $j++) {
                    $a_xml = $area_list_xml[$j];
                    $area_map[(int) $a_xml['id']] = (string) $a_xml['name'];
                }
                $prefecture_map->push(['id' => (int) $p_xml['id'], 'name' => (string) $p_xml['name'], 'area_map' => $area_map]);
            }
            
            Cache::put(self::PREFECTURE_CACHE_KEY, $prefecture_map, 60);
        }

        return $prefecture_map;
    }
}

