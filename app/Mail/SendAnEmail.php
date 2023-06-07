<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAnEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromEmail;
    public $userEmail;
    public $userName;
    public $toName;
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fromEmail, $userEmail, $userName, $toName, $content)
    {
        $this->fromEmail = $fromEmail;
        $this->userEmail = $userEmail;
        $this->userName = $userName;
        $this->toName = $toName;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->fromEmail),
            replyTo: [
                new Address(
                    $this->userEmail,
                    $this->userName
                )
            ],
            subject: 'El usuario ' . $this->userName . ' ha enviado un mensaje',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.mail-Send-template',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
