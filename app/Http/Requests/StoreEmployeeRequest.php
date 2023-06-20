<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreEmployeeRequest extends FormRequest
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

        switch ($this->input('employeeIdSlc')) {
            case 'nif':
                $rules['employeeId'] = 'required|nif|unique:employees,dni';
                break;
            case 'nie':
                $rules['employeeId'] = 'required|nie|unique:employees,dni';
                break;
            default:
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'companyId.nif' => 'El nif no tiene un formato correcto.',
            'companyId.nie' => 'El nie no tiene un formato correcto.',
            'companyId.cif' => 'El cif no tiene un formato correcto.',
            'companyId.unique' => 'Una empresa con éste id ya consta en nuestra base de datos',
            'employeeId.nif' => 'El nif no tiene un formato correcto.',
            'employeeId.nif' => 'El nie no tiene un formato correcto.',
            'employeeId.unique' => 'Una empleado con éste id ya consta en nuestra base de datos',
        ];
    }
}
