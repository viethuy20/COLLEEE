<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Fancrew extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $type;
    protected $options;
    
    private static $SUBJECT_MAP = [1 => 'モニター抽選結果のお知らせ',
        2 => 'モニター応募受付完了',
        3 => 'モニター仮当選のお知らせ',
        4 => 'アンケート提出確認のお知らせ',
        5 => 'アンケート再確認のお願い',
        6 => 'アンケート回答ありがとうございます',
        7 => 'レシート（購入確認）の提出が完了しました。',
        8 => 'レシート再確認のお願い',
        9 => 'レシート確認のお知らせ',
        10 => '納品書の提出が完了しました。',
        11 => '納品書再確認のお願い',
        12 => '納品書確認のお知らせ',
        13 => 'キャンセルのご連絡',
        14 => 'モニター完了のお知らせ',
        15 => 'モニター完了のお知らせ',
        16 => 'モニター期限のご連絡です',
        17 => 'モニター期限延長のお知らせ',
        18 => 'モニター再提出期限のご連絡です',
        19 => '★繰上げ当選★のご案内！',
        20 => 'モニター繰上げ当選結果のお知らせ',
        21 => '購入情報再提出のお願い',
        22 => 'モニター却下のご連絡'];
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, int $type, array $options)
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
        return $this->subject('[GMOポイ活]「'.$this->options['shop']['name'].'」'.self::$SUBJECT_MAP[$this->type])
                ->to(email_quote($this->email))
                ->text('emails.fancrew.t'.$this->type)
                ->with($this->options);
    }
}
