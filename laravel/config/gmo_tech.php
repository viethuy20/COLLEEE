<?php
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return [
        // APIリクエストURL
        'URL' => 'https://wall.smaad.net/wall/672040291/',
    ];
} else {
    return [
        // APIリクエストURL
        'URL' => 'https://wall.smaad.net/wall/885867335/',
    ];
}
