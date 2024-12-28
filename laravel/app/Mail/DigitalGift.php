<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DigitalGift extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $type;
    protected $options;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $type, array $options = [])
    {
        $this->email = $email;
        $this->type = $type;
        $this->options = $options;
    }

    private static $SUBJECT_MAP = [
        'gift' => '【GMOポイ活】ポイント交換実施・デジタルギフトURL発行のお知らせ）',
    ];

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $options = array_merge(['email' => $this->email], $this->options);
        return $this->subject(
            self::$SUBJECT_MAP[$this->type] . '【受付番号:'.
            $this->options['exchange_request_number'].
            '】'
        )
            ->to(email_quote($this->email))
            ->text('emails.digital_gift.'.$this->type)
            ->with($options);
    }
}
