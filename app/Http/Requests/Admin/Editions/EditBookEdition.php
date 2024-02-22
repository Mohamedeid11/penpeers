<?php

namespace App\Http\Requests\Admin\Editions;

use Illuminate\Foundation\Http\FormRequest;

class EditBookEdition extends FormRequest
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
            'visibility' => 'required',
            'original_price' => 'required|integer',
            'discount_price' => 'required|integer',
        ];
    }
}
