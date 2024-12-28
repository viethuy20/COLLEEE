<?php
$base_data = [
    'URL_ABOUT' => 'https://prod-sites.tentame.net/v2/about?siteCode=gmopoikatu&key=',
    'URl_HOW_TO' => 'https://prod-sites.tentame.net/v2/howto?siteCode=gmopoikatu&key=',
    'URL_FAQ' => 'https://prod-sites.tentame.net/v2/faq?siteCode=gmopoikatu&key=',
    'CLIENT_KEY' => 'PCIH7U6W96R03L68W0Z7',
    'CLIENT_SECRET_KEY' => 'L30C76S+R2QKNEAM4X4W58EJEE4W96/O',
    'TAX' => 1.1
];

$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    return array_merge($base_data, [
        'CREATE_USER' => 'https://development-api.tentame.net/v2/user/create/',
        'GET_PROJECT' => 'https://development-api.tentame.net/v2/project/get/',
    ]);
} else {
    return array_merge($base_data, [
        'CREATE_USER' => 'https://prod-api.tentame.net/v2/user/create/',
        'GET_PROJECT' => 'https://prod-api.tentame.net/v2/project/get/',
    ]);
}
