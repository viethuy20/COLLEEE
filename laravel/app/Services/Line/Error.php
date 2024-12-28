<?php

namespace App\Services\Line;

class Error
{
    /** @var array エラーメッセージ */
    private $errorMessages = [
        // 交換情報取得
        'user-referenceNo/get' => [
            '1106' => '該当のLINE Payアカウントが有効ではありません。<a href="https://help.line.me/line/android/pc?lang=ja&contentId=20000669" class="error_message_link">こちらの</a>URLよりご確認ください。',
            '1108' => '意図せぬエラーが発生しました。お問い合わせください。',
            '1121' => 'LINE Payの登録が完了しておりません。<a href="https://lin.ee/OvSXZqP/qjha/howtouselp" class="error_message_link">こちらの</a>URLより新規登録をお願いいたします。',
            '1122' => "登録されている端末が海外端末になっております。",
            '1613' => '意図せぬエラーが発生しました。お問い合わせください。',
            '2101' => '意図せぬエラーが発生しました。お問い合わせください。',
            '9000' => '意図せぬエラーが発生しました。お問い合わせください。',
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
