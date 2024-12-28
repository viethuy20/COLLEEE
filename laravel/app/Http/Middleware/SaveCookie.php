<?php

namespace App\Http\Middleware;

use Closure;

class SaveCookie
{
    /** PR_CODE. */
    const PR_CODE = 'a8';
    /** PR_ID. */
    const PR_ID = 'pr_id';
    /** FID. */
    const FID = 'fid';

    public static $REQUEST_PARAM = [
        self::PR_CODE => 'pr_code',
        self::PR_ID => 'pr_id',
        self::FID => 'fid'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $data_map = [];
        $has_param = false;

        foreach (self::$REQUEST_PARAM as $key => $value) {
            // キーが存在した場合はdata_mapに保存
            if ($request->has($key)) {
                $has_param = true;
                $data_map[$value] = $request->input($key);
            }
        }

        // 対象のリクエストパラメーターが指定されていた場合はクッキーに保存
        if ($has_param) {
            self::saveData((object) $data_map);
        }

        return $next($request);
    }

    public static function getData() {
        $cookie_data = new \stdClass();
        $has_param = false;
        foreach (self::$REQUEST_PARAM as $key => $value) {
            $tmp_cookie_data = filter_input(INPUT_COOKIE, $value);
            if(isset($tmp_cookie_data)) {
                $has_param = true;
                $cookie_data->$value = $tmp_cookie_data;
            }
        }
        // cookieにデータがあった場合、$cookie_dataをreturn
        if ($has_param) {
            return $cookie_data;
        } else {
            return null;
        }
    }
    
    public static function saveData($data) {
        foreach (self::$REQUEST_PARAM as $key => $value) {
            // valueが存在した場合はクッキーに保存
            if (isset($data->$value)) {
                setcookie($value,$data->$value,time()+30*24*3600,"/",".colleee.net");
            }
        }
    }
}
