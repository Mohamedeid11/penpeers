<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $guarded = [];
    protected $trans_fields = ['name'];

    public function occupation(){
        return $this->belongsTo(Occupation::class);
    }
    protected function form_fields(){
        $occupations = Occupation::all();
        $choices = [];
        foreach($occupations as $occupation){
            $choices[$occupation['id']] = $occupation->trans('name');
        }
        return collect([
            'name' => collect([['type'=>'text', 'required'=>3]]),
            'email' => collect([['type'=>'email', 'required'=>3]]),
            'phone' => collect([['type'=>'phone', 'required'=>3]]),
            'occupation_id' => collect([['type'=>'select', 'required'=>3, 'choices' => $choices]]),
        ]);
    }
}
