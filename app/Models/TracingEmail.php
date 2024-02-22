<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TracingEmail extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $fillable = ['book_id', 'subject', 'sender_id', 'receiver_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class , 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}
