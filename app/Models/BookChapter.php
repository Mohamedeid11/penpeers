<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookChapter extends Pivot
{
    use HasFactory;

    protected $table = 'book_chapters';
    public $incrementing = true;
    protected $appends = ['author_id', 'remaining_days_before_deadline', 'lead_author'];
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function book_edition(){
        return $this->belongsTo(BookEdition::class);
    }
    public function authors(){
        return $this->belongsToMany(User::class, 'book_chapter_authors', 'book_chapter_id', 'user_id')
            ->withPivot(['book_edition_id', 'book_id']);
    }
    public function scopeCompleted($query)
    {
        return $query->where('completed', 1);
    }

    public function getAuthorIdAttribute() {
        return $this->authors()->first() ? $this->authors()->first()->id : 0;
    }
    public function getRemainingDaysBeforeDeadlineAttribute()
    {
        return $this->deadline ? Carbon::parse($this->deadline)->diffInDays(Carbon::now()) : NULL;
    }
    public function getLeadAuthorAttribute(){
        return $this? $this->book->lead_author : NULL;
    }
}
