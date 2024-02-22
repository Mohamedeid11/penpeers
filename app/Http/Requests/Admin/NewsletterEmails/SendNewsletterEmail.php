<?php

namespace App\Http\Requests\Admin\NewsletterEmails;

use Illuminate\Foundation\Http\FormRequest;

class SendNewsletterEmail extends FormRequest
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
            "subject" => "required",
            "content" => "required"
        ];
    }
}
