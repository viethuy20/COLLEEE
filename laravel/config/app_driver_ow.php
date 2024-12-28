<?php
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return [
        // APIリクエストURL
        'URL' => 'https://sandbox.appdriver.jp/5/v1/index/',
        'siteid'             => '22465',
        'mediaid'            => '3666',
        'sitekey'            => '5e7ff2041d1e7039994992f4f87f2927',
        'appfrom'            => 'poikatsu',
    ];
} else {
    return [
        // APIリクエストURL
        'URL' => 'https://appdriver.jp/5/v1/index/',
        'siteid'             => '151269',
        'mediaid'            => '4988',
        'sitekey'            => '4ecc356e299c87f4b26080798fa752b3',
        'appfrom'            => 'poikatsu',
    ];
}
