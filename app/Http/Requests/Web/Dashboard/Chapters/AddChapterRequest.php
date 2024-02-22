<?php

namespace App\Http\Requests\Web\Dashboard\Chapters;

use App\Models\Book;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddChapterRequest extends FormRequest
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
        $book = auth()->user()->books()->find($this->route('book'));
        $edition = $book->editions()->where(['edition_number'=>$this->route('edition')])->firstorFail();
        return [
            'name' => 'required',
            'author_id' => 'required|exists:users,id',
            'order' => [
                'required',
                'numeric',
                'min:1',
                Rule::unique('book_chapters', 'order')->where(function (Builder $query) use ($edition){
                    $query->where('book_id', $this->route('book'));
                    $query->where('book_edition_id', $edition->id);
                })
            ]
        ];
    }
}
