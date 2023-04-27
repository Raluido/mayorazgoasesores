<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'comments' => 'required'
        ]);

        $toName = "Mayorazgo Asesores";
        $toEmail = "raul@websiwebs.es";

        $data = array('name' => $fromName, 'body' => $content);
        Mail::send('emails.template', $data, function ($message) use ($toName, $toEmail) {
            $message->to($toEmail, $toName)
                ->subject('El usuario' . $fromName . 'ha enviado un mensaje');
            $message
                ->from($fromEmail, $fromName);
        });
    }
}
