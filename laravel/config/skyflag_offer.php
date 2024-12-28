<?php

$base_data = [
    'key_ios'              => '3kmeOZmkqAy6onHtZhtLUgAdMaGe3DAdMaGe3D',
    'key_android'          => 'bBEjp9cao04sXDRT8WBvLwAdMaGe3DAdMaGe3D',

];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'https://ow.skyflag.jp/ad/p/ow/index',
    ]);
} else {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'https://ow.skyflag.jp/ad/p/ow/index',
    ]);
}
