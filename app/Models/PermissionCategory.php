<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory, Translatable;

    public $trans_fields = ['display_name'];

    public function permissions(){
        return $this->hasMany(Permission::class, 'permission_category_id');
    }
}
