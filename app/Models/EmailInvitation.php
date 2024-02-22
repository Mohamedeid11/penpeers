<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailInvitation extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['status_text'];

    public function scopeActive($query){
        $query->where(['status'=>1,['answered_at', '!=', null]]);
    }
    public function scopePending($query){
        $query->where(['status'=>0]);
    }
    public function scopeRegistered($query){
        $query->where(['status'=>1]);
    }
    public function scopeNotActive($query){
        $query->where('status', '!=', 1);
    }

    public function scopePendingAndRejected($query){
        $query->where(['status'=>2,['answered_at', '!=', null]])->orWhere('status', '0');
    }

    public function book_role (){
        return $this->belongsTo(BookRole::class);
    }
    public function book (){
        return $this->belongsTo(Book::class);
    }
    public function invitor(){
        return $this->belongsTo(User::class, 'invited_by');
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
