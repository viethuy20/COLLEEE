<?php

namespace App\Services\Paypay;

class Error
{
    /** @var array エラーメッセージ */
    private $errorMessages = [
        // 交換情報取得
        '/v1/qr/sessions' => [
            'INVALID_REQUEST_PARAMS' => '無効なデータが含まれています。',
            'UNAUTHORIZED' => '意図せぬエラーが発生しました。お問い合わせください。',
            'RATE_LIMIT' => 'リクエスト制限数超過しています。',
            'INTERNAL_SERVER_ERROR' => "意図せぬエラーが発生しました。お問い合わせください。",
            'EXPECTATION_FAILED' => '意図せぬエラーが発生しました。お問い合わせください。',
            'SESSION_NOT_FOUND' => '意図せぬエラーが発生しました。お問い合わせください。',
        ]
        // ...
    ];

    /**
     * エラーメッセージを取得する
     *
     * @param string $endpoint
     * @param string $errorCode
     * @return string
     */
    public function getErrorMessage(string $endpoint, string $errorCode): string
    {
        return $this->errorMessages[$endpoint][$errorCode] ?? '意図せぬエラーが発生しました。お問い合わせください。';
    }
}
