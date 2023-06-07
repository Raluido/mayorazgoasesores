<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendAnEmail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function send(EmailRequest $request)
    {
        $validated = $request->validated();

        $fromEmail = ENV('MAIL_FROM_ADDRESS');
        $userEmail = $validated['email'];
        $userName = $validated['name'];
        $toName = "Mayorazgo Asesores";
        $content = $validated['content'];

        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new SendAnEmail($fromEmail, $userEmail, $userName, $toName, $content));

        if (Mail::flushMAcros() != null) {
            return redirect()
                ->back()
                ->withErrors(__("Ha habido un error al enviar el mensaje, vuelva a intentarlo más tarde."));
        } else {
            return redirect()
                ->back()
                ->withSuccess(__("El mensage se ha enviado correctamente"));
        }
    }
}
