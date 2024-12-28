<?php
$base_data = [
    'media_id'             => '124',
    'syid'              => 'L8uW9j77',
];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
            // APIリクエストURL
            'URL' => 'https://test-colleee.ib-game.jp/stamp/',
    ]);
} else {
    return array_merge($base_data, [
            // APIリクエストURL
            'URL' => 'https://colleee.ib-game.jp/stamp/',
    ]);
}

