<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    public $trans_fields = ['name'];
    protected $fillable = [
        'name'
    ];
    protected $appends = ['genre_num_of_books', 'genre_num_of_popular_books', 'genre_num_of_books_need_authors'];
    protected function form_fields()
    {
        return collect([
            'name' => collect(['type' => 'text', 'required' => 3])
        ]);
    }
    public function books()
    {
        return $this->hasMAny(Book::class);
    }
    public function getGenreNumOfBooksAttribute()
    {
        $books = $this->books()->where(['status' => 1, 'completed' => 1]);
        $books = request()->filter_type == 'for_sale' ? $books->where('sample', 0) : $books->where('sample', 1);
        return $this->exists ? $books->count() : Null;
    }
    public function getGenreNumOfPopularBooksAttribute()
    {
        return ($this->exists ?
            (auth()->user() ? $this->books()->shared()->published()->popular()->where('completed', 1)->count() :
                $this->books()->public()->published()->popular()->where('completed', 1)->count()) : Null
        );
    }
    public function getGenreNumOfBooksNeedAuthorsAttribute()
    {
        return $this->exists ? $this->books()->whereHas('book_participants', function($query){

            $query->whereHas('book_role', function($Q){

                $Q->where('name', 'lead_author');

            })->whereHas('user', function($q){

                $q->public();

            });

        })->receivable()->where(function($qwry){

            $qwry->where('completed', 0)->orWhere('completed', 2);

        })->count() : Null;
    }
}
