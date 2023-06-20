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
        $rules = array();

        $rules['name'] = 'required|unique:users,name';
        $rules['email'] = 'required|email:rfc,dns|unique:users,email';

        switch ($this->input('companyIdSlc')) {
            case 'nif':
                $rules['companyId'] = 'required|nif|unique:users,nif';
                break;
            case 'nie':
                $rules['companyId'] = 'required|nie|unique:users,nif';
                break;
            case 'cif':
                $rules['companyId'] = 'required|cif|unique:users,nif';
                break;
            default:
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.unique' => 'Una empresa con éste nombre ya consta en nuestra base de datos.',
            'email.unique' => 'Una empresa con éste email ya consta en nuestra base de datos.',
            'companyId.unique' => 'Una empresa con éste id ya consta en nuestra base de datos.',
            'companyId.nif' => 'El nif no tiene un formato correcto.',
            'companyId.nie' => 'El nie no tiene un formato correcto.',
            'companyId.cif' => 'El cif no tiene un formato correcto.',
        ];
    }
}
