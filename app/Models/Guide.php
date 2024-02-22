<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory, HasAdminForm, Translatable;

    protected $guarded = [];
    public $trans_fields = ['name', 'explanation'];
    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3]),
            'explanation' => collect(['type'=>'ckeditor', 'required'=>3])
        ]);
    }
}
