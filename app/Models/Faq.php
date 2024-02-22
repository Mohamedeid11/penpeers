<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Faq extends Model
{
    use HasFactory, Translatable, HasAdminForm;

    protected $guarded = [];

    public $trans_fields = ['question', 'answer'];
    protected function form_fields(){
        return collect([
            'question' => collect(['type'=>'text', 'required'=>3]),
            'answer' => collect(['type'=>'ckeditor', 'required'=>3])
        ]);
    }
}
