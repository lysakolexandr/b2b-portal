<?php
namespace App\Mail;

use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Support\Traits\Conditionable;

use Illuminate\Support\Facades\Mail as Mail;

class CustomMail extends Mail
{

    /**
     * Send a new mailable message instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailable  $mailable
     * @return void
     */
    public function send(MailableContract $mailable)
    {
        config(['MAIL_FROM_ADDRESS' => 'lysak.olexandr@ukr.net']);
        dd(22);
       // $this->mailer->send($this->fill($mailable));
    }

    /**
     * Populate the mailable with the addresses.
     *
     * @param  \Illuminate\Contracts\Mail\Mailable  $mailable
     * @return \Illuminate\Mail\Mailable
     */
    protected function fill(MailableContract $mailable)
    {
        return tap($mailable->to($this->to)
            ->cc($this->cc)
            ->bcc($this->bcc), function (MailableContract $mailable) {
                if ($this->locale) {
                    $mailable->locale($this->locale);
                }
            });
    }

}
