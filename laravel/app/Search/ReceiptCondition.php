<?php
namespace App\Search;

/**
 * プログラム検索条件.
 */
class ReceiptCondition
{
    /**
     *パラメーター.
     */
    protected $params = [];

    protected $key_list;

    public function __construct() {
        $this->key_list = ['page', 'sort', 'limit',
            'brands', 'emoneys', 'insurances', 'annual_free', 'etc', 'apple_pay'];

        $this->params['brands'] = null;
        $this->params['emoneys'] = null;
        $this->params['insurances'] = null;
        $this->params['page'] = 1;
        $this->params['sort'] = 0;
        $this->params['limit'] = 5;	// 検索一覧上限件数
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

    public function getIdList($value) {
        $id_list = [];
        for ($i = 1; $i <= 64; $i++) {
            if ($value >> ($i - 1) & 1 == 1) {
                $id_list[] = $i;
            }
        }
        return $id_list;
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

        $route_list = [];
        // ページ
        $page = $ext->page ?? $default->getParam('page');
        if ($page != 1) {
            $route_list['page'] = $page;
        }
        // 並び順
        $sort = $ext->sort ?? $default->getParam('sort');
        if (!empty($route_list) || $sort != 0) {
            $route_list['sort'] = $sort;
        }

        return route('receipt.list', $route_list);
    }
}
