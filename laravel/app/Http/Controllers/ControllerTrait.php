<?php
namespace App\Http\Controllers;

use URL;

trait ControllerTrait
{
    public function redirectBack()
    {
        // スクロール位置がない場合
        if (!request()->has('scroll')) {
            return redirect()->back();
        }
        
        $url = URL::previous();
        $parsed_url = parse_url($url);
        
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        
        $get_param = ['scroll' => request()->input('scroll')];
        if (isset($parsed_url['query'])) {
            $parsed_query = [];
            parse_str($parsed_url['query'], $parsed_query);
            $get_param = empty($parsed_query) ? $get_param : array_merge($parsed_query, $get_param);
        }
        $query = empty($get_param) ? '' : '?' . http_build_query($get_param);
        
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        
        return redirect("$scheme$user$pass$host$port$path$query$fragment");
    }
}
