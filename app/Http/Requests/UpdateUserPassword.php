<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'password' => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
                'password_confirmation' => 'required',
            ];
    }

    public function messages()
    {
        return [
            'password.required' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
            'password.min' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
            'password_confirmation.required' => 'Tienes que repetir la contraseña del apartado superior aqui',
        ];
    }
}
