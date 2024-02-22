<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookParticipationRequest extends Model
{
    use HasFactory;
    protected $appends = ['days_diff'];
    protected $fillable = ['user_id', 'book_id', 'message', 'bio', 'email', 'name', 'accepted_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function getDaysDiffAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->diffInDays(Carbon::now()) : NULL;
    }
}
