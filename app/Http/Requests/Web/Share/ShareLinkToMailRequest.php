<?php

namespace App\Http\Requests\Web\Share;

use Illuminate\Foundation\Http\FormRequest;

class ShareLinkToMailRequest extends FormRequest
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
            "sender_email" => "sometimes|required|email:rfc,dns",
            "sender_name" => "sometimes|required",
            "receiver_email" => "required|email:rfc,dns",
            "receiver_name" => "required",
            'g-recaptcha-response' => 'required|recaptcha',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Recaptcha is required',
        ];
    }
}
