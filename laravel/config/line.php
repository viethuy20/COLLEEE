<?php

return [

    'line_profile_uri' => 'https://api.line.me/v2/profile',

    'line_token_uri' => 'https://api.line.me/oauth2/v2.1/token',

    'line_authorize_uri' => 'https://access.line.me/oauth2/v2.1/authorize',

    'line_verify_uri' => 'https://api.line.me/oauth2/v2.1/verify',

    'line_refresh_accessToken' => 'https://api.line.me/v2/oauth/revoke',

    'line_pay_uri' => 'https://api-pay.line.me/v3/payments/',

    'line_login_channel_id' =>  env('LINE_LOGIN_CHANNEL_ID'),

    'line_login_channel_secret' =>  env('LINE_LOGIN_CHANNEL_SECRET'),

    'line_pay_channel_id' =>  env('LINE_PAY_CHANNEL_ID'),

    'line_pay_channel_secret' =>  env('LINE_PAY_CHANNEL_SECRET'),
];

