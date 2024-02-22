<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookBuyRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'message', 'phone', 'email', 'name', 'accepted_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
