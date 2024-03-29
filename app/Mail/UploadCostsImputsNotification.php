<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UploadCostsImputsNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $uploadError;
    public $usersCreated;
    public $monthInput;
    public $yearInput;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uploadError, $monthInput, $yearInput)
    {
        $this->uploadError = $uploadError;
        $this->monthInput = $monthInput;
        $this->yearInput = $yearInput;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(ENV('MAIL_FROM_ADDRESS'))
            ->subject('Proceso de envio de imputación de costes finalizado')
            ->view('mails.mail-UploadCostsImputs-template')
            ->with('uploadError', $this->uploadError)
            ->with('monthInput', $this->monthInput)
            ->with('yearInput', $this->yearInput);
    }
}
