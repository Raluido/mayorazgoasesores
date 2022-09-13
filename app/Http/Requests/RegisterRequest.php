<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'nif' => 'required|size:9,unique:users,nif',
            'name' => 'required|unique:users,name',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
            'password.min' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un caracter espacial, un número y una longitud de al menos 10 dígitos',
        ];
    }
}
