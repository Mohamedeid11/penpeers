<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterEmail extends Model
{
    use HasAdminForm;

    protected $fillable = ["subject", "content"];
    protected function form_fields(){
        return collect([
            'subject' => collect(['type'=>'text', 'required'=>3]),
            'content' => collect(['type'=>'ckeditor', 'required'=>3])
        ]);
    }
}
