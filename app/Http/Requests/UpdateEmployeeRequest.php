<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = array();

        switch ($this->input('employeeIdSlc')) {
            case 'nif':
                $rules['employeeId'] = 'required|nif|unique:employees,dni,' . $this->employee->id;
                break;
            case 'nie':
                $rules['employeeId'] = 'required|nie|unique:employees,dni,' . $this->employee->id;
                break;
            default:
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'employeeId.nif' => 'El nif no tiene un formato correcto.',
            'employeeId.nif' => 'El nie no tiene un formato correcto.',
            'employeeId.unique' => 'Una empleado con éste id ya consta en nuestra base de datos',
        ];
    }
}
