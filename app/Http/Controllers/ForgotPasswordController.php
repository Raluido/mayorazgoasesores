<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users',
            ],
            [
                'email.exists' => 'El email introducido no consta en nuestra base de datos'
            ]
        );

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mails.mail-ForgetPassword-template', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Resetear Contraseña');
        });

        return back()->with('message', 'Te hemos enviado un link para recuperar tu contraseña!');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users',
                'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
                'password_confirmation' => 'required'
            ],
            [
                'email.required' => 'Necesitamos tu correo electrónico aqui',
                'password.required' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
                'password.min' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
                'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
                'password_confirmation.required' => 'Tienes que repetir la contraseña del apartado superior aqui',
            ]
        );

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Tu contraseña ha sido cambiada!');
    }
}
