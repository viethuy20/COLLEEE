<?php
namespace App\External;


/**
 * Description of SkyFlag
 *
 * @author y_oba
 */
class SkyFlag
{

    /**
     * 設定値取得.
     * @param string $key キー
     * @return mixed 設定値
     */
    public static function getConfig(string $key)
    {
        return config('skyflag.'.$key);
    }
}
