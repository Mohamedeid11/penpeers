<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookRequestRequest extends FormRequest
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
        if (Auth::user()) {
            return [
                'terms' => 'required'
            ];
        } else {
            return [
                'name' => 'required',
                'email' => 'required|email:rfc,dns',
                'bio' => 'required',
                'terms' => 'required',
                'g-recaptcha-response' => 'required|recaptcha',
            ];
        }
    }
    public function getRedirectUrl()
    {
        return route('web.send_book_request', ['slug' => $this->slug]) . "#tabs-container";
    }
}
