<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean'
    ];
    public $trans_fields = ['name'];
    public function scopeActive($query){
        $query->where('active', 1);
    }
    
    protected function form_fields(){
        return collect([
            'name' => collect(['type'=>'text', 'required'=>3]),
            'link' => collect(['type'=>'text', 'required'=>3]),
            'icon' => collect(['type'=>'text', 'required'=>3]),
        ]);
    }
}
