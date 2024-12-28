<?php

$base_data = [
    'media_id'             => '123',
    'md5_key'              => 'UkEC5jLUiLqdbHq8hv4xMb8vjAz7uBFe',
    'point_unit'           => '抽選券',
    'exchange_point_unit'  => 'ポイント（1ポイント=1円）',
    'bonus_get'            => '1週間',

];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
            // APIリクエストURL
            'URL' => 'https://gaingames.gesoten.com',
    ]);
} else {
    return array_merge($base_data, [
            // APIリクエストURL
            'URL' => 'https://colleee.kantangame.com',
    ]);
}
