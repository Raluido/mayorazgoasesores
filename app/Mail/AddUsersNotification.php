<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddUsersNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $usersNifPass;
    public $uploadError;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usersNifPass, $uploadError)
    {
        $this->usersNifPass = $usersNifPass;
        $this->uploadError = $uploadError;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mayorazgoasesores.info@gmail.com')->subject('Nuevos registros de empresas')->view('mails.mail-AddUsers-template')->with('usersNifPass', $this->usersNifPass)->with('uploadError', $this->uploadError);
    }
}
