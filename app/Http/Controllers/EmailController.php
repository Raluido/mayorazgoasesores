<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function send(EmailRequest $request)
    {

        $request->validated();

        $fromEmail = "mayorazgoasesores.info@gmail.com";
        $userEmail = $request->input('email');
        $userName = $request->input('name');
        $toName = "Mayorazgo Asesores";
        $toEmail = "raul@websiwebs.es";
        $content = $request->input('content');

        Mail::send('mails.mail-Send-template', ['name' => $userName, 'body' => $content], function ($message) use ($toName, $toEmail, $userName, $userEmail, $fromEmail) {
            $message->from($fromEmail, $userName);
            $message->subject('El usuario' . $userName . 'ha enviado un mensaje');
            $message->to($toEmail, $toName);
            $message->replyTo($userEmail, $userName);
        });

        return redirect()->back();
    }
}
