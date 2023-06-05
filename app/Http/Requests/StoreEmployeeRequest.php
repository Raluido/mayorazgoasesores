<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'nif' => 'required|size:9',
            'dni' => 'required|size:9|unique:employees,dni',
        ];
    }

    public function messages()
    {
        return [
            'nif.size' => 'El nif debe estar formado por nueve elementos',
            'dni.size' => 'El dni debe estar formado por nueve elementos',
            'dni.unique' => 'El trajador ya consta en nuestra base de datos',
        ];
    }
}
