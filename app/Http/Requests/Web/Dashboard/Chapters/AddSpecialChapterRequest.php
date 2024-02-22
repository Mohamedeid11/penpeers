<?php

namespace App\Http\Requests\Web\Dashboard\Chapters;

use Illuminate\Foundation\Http\FormRequest;

class AddSpecialChapterRequest extends FormRequest
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
            'special_chapter_id' => 'required|exists:special_chapters,id',
            // 'author_id' => 'required|exists:users,id',
            // 'deadline'=>'required'
        ];
    }
}
