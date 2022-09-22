<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UploadPayrollsNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $uploadError;
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
        return $this->from('mayorazgoasesores.info@gmail.com')
            ->subject('Proceso de envio de nóminas finalizado')
            ->view('mails.mail-UploadPayrolls-template')
            ->with('uploadError', $this->uploadError)
            ->with('monthInput', $this->monthInput)
            ->with('yearInput', $this->yearInput);
    }
}
