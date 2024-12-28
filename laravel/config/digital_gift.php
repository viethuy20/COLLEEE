<?php
$base_data = [
    'prefix' => 'PD',
    'gift_key' => 'wpcXmRyiiG7Sn7vTZfJTSLX7FfDdmUwu7vKe',
    'purchase_key' => 'jn27KK6mKY57LTZQgn9JNFzAh4T8zqjuxSdz',
    'type' => \App\UserPoint::OTHER_POINT_TYPE,
    'totp_steps'=>15,//TOTPのステップ数
    'totp_digest_algorithm'=>'sha256',//TOTPの暗号化方式
    'totp_digits'=>8,//TOTPの桁数
    'purchase_name'=>'GMOポイ活',
    'purchase_issuer'=>'GMO NIKKO株式会社',
    'response_code' => [
        
    ],
    'status' => [0 => 'ポイント交換済み', 1 => 'エラー（ポイント返却済み）', 2 => '申し込み中']
];
$env = env('APP_ENV');
if ($env == 'local') {
    return array_merge($base_data, [
        // 認証URL
        'digital_gift_uri' =>'https://demo.digital-gift.jp/api/partner/',
        'api_key'=>'0938caf7a61e58ca4a75ffb4394283031fe38967',
        'secret_key'=>'MM4GIZDEHEZDIYTEGM4GIYJVMRSTKNBSMIZTKZJYGMZGCYZYGQ3DSMBTGRRDCZRW'

    ]);
} elseif ($env == 'development') {
    return array_merge($base_data, [
        // 認証URL
        'digital_gift_uri' =>'https://demo.digital-gift.jp/api/partner/',
        'api_key'=>'0938caf7a61e58ca4a75ffb4394283031fe38967',
        'secret_key'=>'MM4GIZDEHEZDIYTEGM4GIYJVMRSTKNBSMIZTKZJYGMZGCYZYGQ3DSMBTGRRDCZRW'
    ]); 
} else {
    return array_merge($base_data, [
        'digital_gift_uri' =>'https://digital-gift.jp/api/partner/',
        'api_key'=>'8abae0453827079c1aa183d4c041e7ce5d51583b',
        'secret_key'=>'GZRTQOLDG44TEZJSGU4DIOJRMEYWMZBZG4YGKNJQMY3TQOBWHA4TMN3CMY4GMODC'
    ]);
}
