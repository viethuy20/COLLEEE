<?php
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return [
        'M_ID' => '103330',
        'API_KEY' => '59463f8b08c4e2a9',
    ];
} else {
    return [
        'M_ID' => '103328',
        'API_KEY' => '86063f6132f202f9',
    ];
}