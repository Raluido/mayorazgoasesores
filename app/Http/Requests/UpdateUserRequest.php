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
            'nif' => 'required|size:9,unique:users,nif',
            'dni' => 'required|size:9,unique:employees,nif',
            'name' => 'required|unique:users,name',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
        ];

    }

    public function messages() {
        return [
            'nif.size' => 'El nif debe estar compuesto por 9 elementos',
            'dni.size' => 'El dni debe estar compuesto por 9 elementos',
        ];
    }
}
