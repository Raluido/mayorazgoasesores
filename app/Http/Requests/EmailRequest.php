<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Rules\ReCaptchaEnterpriseRule;

class EmailRequest extends FormRequest
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
            'name' => 'required|max:30',
            'email' => 'required|email|max:30',
            'content' => 'required|max:600',
            'g-recaptcha-action' => 'required|string',
            'g-recaptcha-response' => ['required', new ReCaptchaEnterpriseRule]
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'Por favor, el campo nombre no puede exceder de los 30 caracteres.',
            'email.max' => 'Por favor, el campo email no puede exceder de los 30 caracteres.',
            'content.max' => 'Por favor, el campo de comentarios no puede exceder de los 600 caracteres.',
            'g-recaptcha-action.required' => 'La verificación ha fallado.',
            'g-recaptcha-response.required' => 'Error de verificación de la captcha para robots. Inténtalo más tarde o contacta con nosotros.',
            'g-recaptcha-response.captcha' => 'Error de verificación de la captcha para robots. Inténtalo más tarde o contacta con nosotros.',
        ];
    }
}
