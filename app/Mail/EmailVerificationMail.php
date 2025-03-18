<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $userName;

    /**
     * Criar uma nova instância do Mailable.
     */
    public function __construct($userName, $verificationCode)
    {
        $this->userName = $userName;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Criar a mensagem do e-mail.
     */
    public function build()
    {
        return $this->subject('30 Semanas | Verificação de e-mail')
                    ->markdown('emails.verify-email')
                    ->with([
                        'userName' => $this->userName,
                        'verificationCode' => $this->verificationCode,
                    ]);
    }
}
