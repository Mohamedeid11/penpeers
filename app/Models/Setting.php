<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    protected $guarded = [];
    public $trans_fields = ['display_name'];
    protected function form_fields()
    {
        $settings = Setting::all();
        $inputs = [];
        foreach($settings as $setting){
            $inputs[$setting->name] = collect([
                'type' => $setting->input_type,
                'required' => 0
            ]);
        }
        return collect($inputs);
    }
}
