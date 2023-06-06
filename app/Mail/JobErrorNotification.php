<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $jobError;
    public $exception;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobError, $exception)
    {
        $this->jobError = $jobError;
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(ENV('MAIL_FROM_ADDRESS'))
            ->subject('Error en el envío')
            ->view('mails.mail-JobError-template')
            ->with('jobError', $this->jobError)
            ->with('exception', $this->exception);
    }
}
