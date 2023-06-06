<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $passed;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($passed)
    {
        $this->passed = $passed;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(ENV('MAIL_FROM_ADDRESS'))
            ->subject('Proceso de eliminación de toda la base de datos completada')
            ->view('mails.mail-Delete-template')
            ->with('passed', $this->passed);
    }
}
