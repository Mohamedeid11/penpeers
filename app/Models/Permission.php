<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, Translatable;
    public $trans_fields = ['display_name'];

    public function permission_category(){
        return $this->belongsTo(PermissionCategory::class);
    }
}
