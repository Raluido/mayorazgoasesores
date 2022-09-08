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
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobError)
    {
        $this->jobError = $jobError;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mayorazgoasesores.info@gmail.com')->subject('Error en el envío de la imputación de costes')->view('mails.mail-JobError-template')->with('jobError', $this->jobError);
    }
}
