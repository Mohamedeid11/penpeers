<?php

namespace App\Http\Requests\Web\Dashboard\Editions;

use Illuminate\Foundation\Http\FormRequest;

class AddEditionRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'genre_id' => 'required',
            'language' => 'required',
            'front_cover' => 'required|image',
            'back_cover' => 'required|image',
        ];
    }
}
