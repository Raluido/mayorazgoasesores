<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\Rules\Exists;
use DB;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('intranet.index');
        }

        $errors = array();

        if (isset($credentials['email'])) {
            $email = DB::table('users')->where('email', '=', $credentials['email'])->get();
            if (!empty($email[0])) {
                $errors = ['password' => 'El password utilizado no se encuentra en nuestra base de datos.'];
            } else {
                $errors = ['nif' => 'El email o el nif utilizado no se encuentra en nuestra base de datos.'];
            }
        } elseif (isset($credentials['nif'])) {
            $nif = DB::table('users')->where('nif', '=', $credentials['nif'])->get();
            if (!empty($nif[0])) {
                $errors = ['password' => 'El password utilizado no se encuentra en nuestra base de datos.'];
            } else {
                $errors = ['nif' => 'El email o el nif utilizado no se encuentra en nuestra base de datos.'];
            }
        }

        return back()->withErrors($errors);
    }
}
