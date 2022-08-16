<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMails extends Mailable
{
    use Queueable, SerializesModels;

    public $usersNifPass;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usersNifPass)
    {
        $this->usersNifPass = $usersNifPass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mayorazgoasesores.info@gmail.com')->subject('Nuevos registros de empresas')->view('mails-template')->with('usersNifPass', $this->usersNifPass);
    }
}
