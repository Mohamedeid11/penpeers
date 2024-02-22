<?php

namespace App\Http\Requests\Web\Dashboard\Editions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEditionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'genre_id' => 'required',
            'language' => 'required',
            'front_cover' => 'nullable|image',
            'back_cover' => 'nullable|image',
            'visibility' => 'required|in:public,shared,private'
        ];
    }
}
