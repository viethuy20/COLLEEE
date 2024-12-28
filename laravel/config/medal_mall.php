<?php

$base_data = [
    'exchange_point_unit'  => 'ポイント（1ポイント=1円）',
];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'http://stg.colleee.content-lump.net/wall.php',
    ]);
} else {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'http://colleee.content-lump.net/wall.php',
    ]);
}
