<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'country_id'   => 'required|exists:countries,id',
            'terms' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ];
    }
}
