<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, HasAdminForm, Translatable;

    protected $guarded = [];
    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3]),
            'email' => collect(['type'=>'email', 'required'=>3])
        ]);
    }
}
