<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Support extends Mailable
{
    use Queueable, SerializesModels;

    protected $type;
    protected $options;
    
    private static $SUBJECT_MAP = [
        'withdrawal_info' => '【GMOポイ活】退会者情報',
        'support' => '【GMOポイ活】ご意見箱',
        'inquiry_info' => '【GMOポイ活】問い合わせ'];
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $type, array $options)
    {
        //
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
        return $this->subject(self::$SUBJECT_MAP[$this->type])
            ->to(config('mail.support_to'))
            ->text('emails.support.'.$this->type)
            ->with($this->options);
    }
}
