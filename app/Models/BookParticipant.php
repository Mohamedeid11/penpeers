<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class BookParticipant extends Pivot
{
    protected $table = 'book_participants';
    protected $guarded = [];
    public $incrementing = true;

    protected $appends = ['book_special_chapters', 'book_chapters','status_text'];
    public function scopeActive($query)
    {
        $query->where(['status' => 1]);
    }
    public function scopePending($query)
    {
        $query->where(['status' => 0]);
    }

    public function scopeNotActive($query)
    {
        $query->where('status','!=', 1);
    }
    
    public function book_role()
    {
        return $this->belongsTo(BookRole::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeCurrentUser($query)
    {
        $query->where('user_id', Auth::id());
    }
    public function scopeNotALeadAuthor($query)
    {
        $query->where('book_role_id', '!=', 1);
    }
    public function scopeLeadAuthor($query, $book_id)
    {
        $query->where('book_role_id', '=', 1)->where('book_id', $book_id);
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function getBookSpecialChaptersAttribute()
    {
        return $this->book->book_special_chapters()->whereHas('authors', function (Builder $query) {
            $query->where(['user_id' => $this->user->id]);
        })->get();
    }
    public function getBookChaptersAttribute()
    {
        return $this->book->book_chapters()->whereHas('authors', function (Builder $query) {
            $query->where(['user_id' => $this->user->id]);
        })->get();
    }

    public function getStatusTextAttribute()
    {
        $text = "";
       if($this->status == 0)
       {
        $text = "Pending";
       }else if ($this->status == 1)
       {
        $text = "Accepted";
       }else if ($this->status == 2)
       {
        $text = "Rejected";
       }
       return $text;
    }
}
