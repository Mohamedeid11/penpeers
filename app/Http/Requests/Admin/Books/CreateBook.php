<?php

namespace App\Http\Requests\Admin\Books;

use Illuminate\Foundation\Http\FormRequest;

class CreateBook extends FormRequest
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
            'author_id' => 'required|exists:users,id',
            'genre_id' => 'required|exists:genres,id',
            'title' => 'required',
            'description' => 'required|min:10',
            'front_cover' => 'required|image',
            'back_cover' => 'required|image',
            'language' => 'required',
            'visibility' => 'required'
        ];
    }
}
