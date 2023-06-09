<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Una empresa con éste nombre ya consta en nuestra base de datos',
            'email.unique' => 'Una empresa con éste email ya consta en nuestra base de datos',
            'nif.unique' => 'Una empresa con éste nif ya consta en nuestra base de datos',
        ];
    }
}
