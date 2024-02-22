<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateEmployee extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'email', 'name'];
    protected $appends = ['status', 'username', 'public', 'employee_id', 'arrange'];

    public function getPublicAttribute()
    {
        if ($this->user) {
            if ($this->user->is_public) {
                return $this->user->is_public;
            }
        }
        return 0;
    }

    public function getArrangeAttribute()
    {
        if ($this->user) {
            return $this->user->arrange;
        }
        return 0;
    }

    public function getEmployeeIdAttribute()
    {
        if ($this->user) {
            if ($this->user->id) {
                return $this->user->id;
            }
        }
        return 0;
    }

    public function getStatusAttribute()
    {
        if ($this->user) {
            return "Signed Up";
        } else {
            return "Invitation Sent";
        }
    }

    public function getUserNameAttribute()
    {
        if ($this->user) {
            return $this->user->username;
        } else {
            return "not found";
        }
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
