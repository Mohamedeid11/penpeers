<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPublishRequest extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['status_text'];
    protected $casts = ['approved' => 'boolean'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function edition()
    {
        return $this->belongsTo(BookEdition::class, 'book_edition_id');
    }
    public function getStatusTextAttribute()
    {
    }
}
