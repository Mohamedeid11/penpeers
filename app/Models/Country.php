<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $casts = [
        'active' => 'boolean',
    ];
    protected $fillable = [
        'name',
        'nationality',
        'key',
        'image',
        'active',
    ];
    public $trans_fields = ['name', 'nationality'];
    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3]),
            'nationality' => collect(['type'=>'text', 'required'=>3]),
            'key' => collect(['type'=>'text', 'required'=>3]),
            'image' => collect(['type'=>'file', 'required'=>2]),
            'active' => collect(['type'=>'checkbox']),
        ]);
    }
}
