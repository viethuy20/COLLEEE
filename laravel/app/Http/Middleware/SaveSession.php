<?php

namespace App\Http\Middleware;

use Closure;

class SaveSession
{    
    const SAVE_SESSION_KEY = 'save_session';
    
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
        if ($request->session()->has(self::SAVE_SESSION_KEY)) {
            $data_map = (array) self::getData();
        }
        
        $has_param = false;
        $param_list = [config('share.friend_key')];
        foreach ($param_list as $key) {
            // キーが存在した場合はセッションに保存
            if ($request->has($key)) {
                $has_param = true;
                $data_map[$key] = $request->input($key);
            }
        }
        // データが更新された場合は保存
        if ($has_param) {
            self::saveData((object) $data_map);
        }
        
        return $next($request);
    }
    
    public static function getData() {
        return session()->get(self::SAVE_SESSION_KEY);
    }
    
    public static function saveData($data) {
        session()->put(self::SAVE_SESSION_KEY, $data);
    }
}
