<?php
$base_data = [
    'cid'             => '2606705',
    'pid'              => '2910',
];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'https://trk301.com/',
    ]);
} else {
    return array_merge($base_data, [
        // APIリクエストURL
        'URL' => 'https://trk301.com/',
    ]);
}
