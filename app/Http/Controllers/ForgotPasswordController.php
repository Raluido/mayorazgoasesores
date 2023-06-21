<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Hash;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterRequest;
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
        // Let's get the route param by name to get the User object value
        $user = request()->route('user');

        $request->validate(
            [
                'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
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
            $message->from(ENV('MAIL_FROM_ADDRESS'));
            $message->to($request->email);
            $message->subject('Resetear Contraseña');
        });

        return back()
            ->with('message', 'Te hemos enviado un link a tu cuenta de correo para recuperar tu contraseña!');
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
    public function submitResetPasswordForm(RegisterRequest $request)
    {
        $validated = $request->validate();

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()
                ->withInput()
                ->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')
            ->with('message', 'Tu contraseña ha sido cambiada!');
    }
}
