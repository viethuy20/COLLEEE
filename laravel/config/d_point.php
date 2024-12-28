<?php
$base_data = [
    'prefix' => 'PD',
    'type' => \App\UserPoint::OTHER_POINT_TYPE,
    'response_code' => [
        'M010255' => '送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。',
        'M010095' => '入力パラメータエラーが発生しています。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。',
        'M110659' => '指定されたDクラブ会員番号が存在しません。',
        'M110663' => '指定されたDクラブ会員番号が無効です。',
    ],
    'status' => [0 => 'ポイント交換済み', 1 => 'エラー（ポイント返却済み）', 2 => '申し込み中']
];
$env = env('APP_ENV');
if ($env == 'local') {
    return array_merge($base_data, [
        // 認証URL
        'AuthURL' => 'https://id.smt.docomo.ne.jp/cgi8/oidc/authorize',
        'API2_2_URL' => 'https://conf.uw.docomo.ne.jp/token',
        'API_HOST' => 'conf.uw.docomo.ne.jp',
        'API2_3_URL' => 'https://conf.uw.docomo.ne.jp/userinfo',
        'CLIENT_ID' => 'g00_0478_0002_00',
        'CLIENT_SECRET' => 'feTJ9SPDnD5pf8LWEZchCfScVKkGrqN4',
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        'REDIRECT_URI' => 'https://dev04.colleee.net/d_point/account',
        'LOGOUT_URL' => 'https://id.smt.docomo.ne.jp/cgi8/id/relogin'
    ]);
} elseif ($env == 'development') {
    return array_merge($base_data, [
        // 認証URL
        'AuthURL' => 'https://id.smt.docomo.ne.jp/cgi8/oidc/authorize',
        'API2_2_URL' => 'https://conf.uw.docomo.ne.jp/token',
        'API_HOST' => 'conf.uw.docomo.ne.jp',
        'API2_3_URL' => 'https://conf.uw.docomo.ne.jp/userinfo',
        'CLIENT_ID' => 'g00_0478_0002_00',
        'CLIENT_SECRET' => 'feTJ9SPDnD5pf8LWEZchCfScVKkGrqN4',
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        'REDIRECT_URI' => 'https://dev04.colleee.net/d_point/account',
        'LOGOUT_URL' => 'https://id.smt.docomo.ne.jp/cgi8/id/relogin'
    ]); 
} else {
    return array_merge($base_data, [
        // 認証URL
        'AuthURL' => 'https://id.smt.docomo.ne.jp/cgi8/oidc/authorize',
        // APIリクエストURL
        'API2_2_URL' => 'https://conf.uw.docomo.ne.jp/token',
        'API_HOST' => 'conf.uw.docomo.ne.jp',
        'API2_3_URL' => 'https://conf.uw.docomo.ne.jp/userinfo',
        'CLIENT_ID' => 'g00_0478_0001_00',
        'CLIENT_SECRET' => 'smCGTc69eTxufEmSAMA6CB63cVyUU4Ez',
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        'REDIRECT_URI' => 'https://colleee.net/d_point/account',
        'LOGOUT_URL' => 'https://id.smt.docomo.ne.jp/cgi8/id/relogin'
    ]);
}
