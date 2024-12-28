<?php

$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return  [
            // APIリクエストURL
            'URL' => 'https://dev.colleee.dcontech.net/campain?camp=R5tdxL',
    ];
} else {
    return [
            // APIリクエストURL
            'URL' => 'https://colleee.dcontech.net/campain?camp=R5tdxL',
    ];
}
