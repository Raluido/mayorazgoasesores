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
        $validated = $request->validated();

        $fromEmail = "mayorazgoasesores.info@gmail.com";
        $userEmail = $validated['email'];
        $userName = $validated['name'];
        $toName = "Mayorazgo Asesores";
        $toEmail = "f.luis@mayorazgoasesores.es";
        $content = $validated['content'];
        Mail::send('mails.mail-Send-template', ['name' => $userName, 'body' => $content], function ($message) use ($toName, $toEmail, $userName, $userEmail, $fromEmail) {
            $message->from($fromEmail, $userName);
            $message->subject('El usuario ' . $userName . ' ha enviado un mensaje');
            $message->to($toEmail, $toName);
            $message->replyTo($userEmail, $userName);
        });

        if (count(Mail::failures()) > 0) {
            return redirect()->back()->withErrors(__("Ha habido un error al enviar el mensaje, vuelva a intentarlo más tarde."));
        } else {
            return redirect()->back()->withSuccess(__("El mensage se ha enviado correctamente"));
        }
    }
}
