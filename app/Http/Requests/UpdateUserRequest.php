<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        // Let's get the route param by name to get the User object value
        $user = request()->route('user');

        return [
            'name' => 'required|unique:users,name,' . $user->id,
            'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Necesitas rellenar el campo nombre',
            'name.unique' => 'Ese nombre ya está en uso',
            'email.unique' => 'Ese email ya está en uso',
            'email.required' => 'Necesitas rellenar el campo email',
            'email.email' => 'El email es incorrecto',
        ];
    }
}
