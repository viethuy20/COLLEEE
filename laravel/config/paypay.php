<?php
$base_data = [
    'prefix' => 'PD',
    'type' => \App\UserPoint::OTHER_POINT_TYPE,
    'success' => 'SUCCESS',
    'help_url' => 'https://paypay.ne.jp/guide/point/',
    'response_code' => [
        'INVALID_REQUEST_PARAMS'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //リクエストにより提供された情報に無効なデータが含まれています。
        'OP_OUT_OF_SCOPE'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //操作は許可されていません。
        'MISSING_REQUEST_PARAMS'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //設定されたパラメータが無効です。
        'UNAUTHORIZED'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //有効なapi keyとsecretが提供されていません。
        'OPA_CLIENT_NOT_FOUND'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //OPAクライアントが見つかりません。
        'VALIDATION_FAILED_EXCEPTION'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //リクエストパラメータの処理で問題が発生したことを意味します
        'FAILURE'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。',//トランザクションが重複しています。
        'RESOURCE_NOT_FOUND'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //キャンペーンが見つかりません。
        'UNAUTHORIZED_ACCESS'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //リソースサーバーへの不正アクセスです。
        'TRANSACTION_NOT_FOUND'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //トランザクションが存在しません。
        'BALANCE_OUT_OF_LIMIT'=>'送信データの整合性エラーが発生しております。お手数ですがポイント交換の再実行お願い致します。詳細をご確認なさりたい場合は、お客様サポートからお問い合わせお願いいたします。', //付与対象ユーザーの残高が制限を超過します。
        'INVALID_USER_AUTHORIZATION_ID'=>'指定されたユーザー認証IDが無効です。', //指定したuserAuthorizationId(PayPayのユーザー認可ID)が無効です。
        'EXPIRED_USER_AUTHORIZATION_ID'=>'ユーザー認証IDの有効期限が切れています。', //ユーザー認証IDの有効期限が切れています。
        'INTERNAL_SERVICE_ERROR'=>'内部サービスエラーが発生しました。お手数ですがポイント交換の再実行お願い致します。' //内部サービスエラーが発生しました。
    ],
    'status' => [0 => 'ポイント交換済み', 1 => 'エラー（ポイント返却済み）', 2 => '申し込み中', 5 => '申し込み中', 6 => '申し込み中']
];
$env = env('APP_ENV');
if ($env == 'local' || $env == 'testing') {
    return array_merge($base_data, [
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        // LINE PAYポイント進呈API
        'paypay_base_uri' => 'https://stg-api.sandbox.paypay.ne.jp',
        'paypay_api_key' => 'a_iTUjgbLUaK_bRW9',
        'paypay_merchant_id' => '692958820189184000',
        'paypay_secret' => 'lejNpFp6s5MohqqmPEQg+4xHmuGu5lGiZAXi1aitL0c=',
        'paypay_redirect_url' => 'http://localhost:8080/paypay/account',
    ]);
} elseif ($env == 'development') {
    return array_merge($base_data, [
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        // ポイント進呈API
        'paypay_base_uri' => 'https://stg-api.sandbox.paypay.ne.jp',
        'paypay_api_key' => 'a_iTUjgbLUaK_bRW9',
        'paypay_merchant_id' => '692958820189184000',
        'paypay_secret' => 'lejNpFp6s5MohqqmPEQg+4xHmuGu5lGiZAXi1aitL0c=',
        'paypay_redirect_url' => 'https://dev03.colleee.net/paypay/account',
    ]);
} else { //本番
    return array_merge($base_data, [
        // プロキシ
        'PROXY' => false,
        // SSL証明書
        'SSL_VERIFY' => false,
        // ポイント進呈API
        'paypay_base_uri' => 'https://api.paypay.ne.jp',
        'paypay_api_key' => 'a_WrVf2MrtlX_pVbU',
        'paypay_merchant_id' => '685518093792411648',
        'paypay_secret' => 'opyyn4OAfSa+EpUdIdqTZA8sgYbTWwwLH/7SSC8bc80=',
        'paypay_redirect_url' => 'https://colleee.net/paypay/account',
    ]);
}
