<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookSpecialChapter extends Pivot
{
    use HasFactory;
    public $incrementing = true;

    protected $table = 'book_special_chapters';
    protected $fillable = ['completed','completed_at','deadline', 'book_edition_id', 'book_id', 'book_edition_id', 'special_chapter_id'];
    protected $appends = ['author_id', 'remaining_days_before_deadline', 'lead_author'];

    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function book_edition(){
        return $this->belongsTo(BookEdition::class);
    }
    public function special_chapter(){
        return $this->belongsTo(SpecialChapter::class);
    }
    public function authors(){
        return $this->belongsToMany(User::class, 'book_special_chapter_authors', 'book_special_chapter_id', 'user_id');
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
