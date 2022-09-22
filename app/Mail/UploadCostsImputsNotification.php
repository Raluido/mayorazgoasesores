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
    public function __construct($uploadError, $usersCreated, $monthInput, $yearInput)
    {
        $this->uploadError = $uploadError;
        $this->usersCreated = $usersCreated;
        $this->month = $monthInput;
        $this->year = $yearInput;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mayorazgoasesores.info@gmail.com')
            ->subject('Proceso de envio de imputación de costes finalizado')
            ->view('mails.mail-UploadCostsImputs-template')
            ->with('uploadError', $this->uploadError)
            ->with('usersCreated', $this->usersCreated)
            ->with('monthInput', $this->monthInput)
            ->with('yearInput', $this->yearInput);
    }
}
