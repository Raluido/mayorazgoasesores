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
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uploadError)
    {
        $this->uploadError = $uploadError;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mayorazgoasesores.info@gmail.com')->subject('Proceso de envio de nóminas finalizado')->view('uploadError-template')->with('uploadError', $this->uploadError);
    }
}
