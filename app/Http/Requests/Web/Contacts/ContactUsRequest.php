<?php

namespace App\Http\Requests\Web\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            "first_name" => "required|min:3",
            "last_name" => "required|min:3",
            "email" => "required|email:rfc,dns",
            "message" => "required",
            'g-recaptcha-response' => 'required|recaptcha',
        ];
    }
}
