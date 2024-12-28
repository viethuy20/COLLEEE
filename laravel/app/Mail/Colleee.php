<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Colleee extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $type;
    protected $options;

    private static $SUBJECT_MAP = [
        'entry' => '【GMOポイ活】本登録のお願い',
        'entry_complite' => '【GMOポイ活】登録が完了しました！',
        'withdrawal_complite' => '【GMOポイ活】退会完了',
        'confirm_email' => '【GMOポイ活】基本情報変更のURLをお送りしました',
        'confirm_tel' => '【GMOポイ活】基本情報変更のURLをお送りしました',
        'confirm_password' => '【GMOポイ活】パスワード再設定URLをお送りしました',
        'store_password' => '【GMOポイ活】パスワード変更を完了しました',
        'store_tel' => '【GMOポイ活】電話番号変更を完了しました',
        'exchange' => '【GMOポイ活】交換受付完了しました',
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $type, array $options = [])
    {
        //
        $this->email = $email;
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $options = array_merge(['email' => $this->email], $this->options);
        return $this->subject(self::$SUBJECT_MAP[$this->type])
            ->to(email_quote($this->email))
            ->text('emails.colleee.'.$this->type)
            ->with($options);
    }
}
