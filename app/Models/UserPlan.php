<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserPlan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['remaining'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function getRemainingAttribute()
    {
        $now = Carbon::now();
        return new Carbon($this->end_date) >= $now ? (new Carbon($this->end_date))->diffInDays($now) : -1;
    }
}
