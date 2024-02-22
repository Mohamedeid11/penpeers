<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, HasAdminForm, Translatable;
    protected $guarded = [];
    protected $casts = [
        'is_system' => 'boolean',
    ];
    public $trans_fields = ['content'];

    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3, 'disabled'=>$this->exists && $this->is_system]),
            'content' => collect(['type'=>'ckeditor', 'required'=>3]),
        ]);
    }
}
