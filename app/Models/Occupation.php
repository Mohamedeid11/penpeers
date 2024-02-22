<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    public $trans_fields = ['name'];
    protected $guarded = [];

    protected function form_fields(){
        return collect([
            'name' => collect([['type'=>'text', 'required'=>3]])
        ]);
    }

}
