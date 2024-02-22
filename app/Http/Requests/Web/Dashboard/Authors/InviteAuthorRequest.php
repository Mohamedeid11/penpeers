<?php

namespace App\Http\Requests\Web\Dashboard\Authors;

use Illuminate\Foundation\Http\FormRequest;

class InviteAuthorRequest extends FormRequest
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
        if($this->register_type == 1){
            return [
                'name' => 'sometimes',
                'email.0.*' => 'required|email:rfc,dns|exists:users,email',
            ];
        }

        return [
            'name' => 'sometimes',
            'email' => 'required|email:rfc,dns|max:255',
        ];

    }
}
