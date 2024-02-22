<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Traits\HasAdminForm;

class Plan extends Model
{
    use HasFactory, HasAdminForm;
    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean',
        'is_system' => 'boolean',
    ];
    protected $appends = ['day_price', 'years'];
    public function form_fields()
    {
        return collect([
            'account_type' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => ['personal' => "Personal", 'corporate' => "Corporate"]
            ]),
            'period' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => [
                    'annually' => "Annually", 'biennially' => "Biennially",
                    'triennially' =>  "Triennially", 'quadrennially' => "Quadrennially"
                ]
            ]),
            'active' => collect(['type' => 'checkbox', 'default' => true]),
            'price' => collect(['type' => 'number', 'required' => 3]),
            'items' => collect(['type' => 'number', 'required' => 0]),
            'name'  => collect(['type' => 'text', 'required' => 3])
        ]);
    }
    public function getDayPriceAttribute()
    {
        return $this->price / ($this->years * 365);
    }
    public function getYearsAttribute()
    {
        switch ($this->period) {
            case 'biennially':
                return 2;
            case 'triennially':
                return 3;
            case 'quadrennially':
                return 4;
            default:
                return 1;
        }
    }
    public function priceForUser($user)
    {
        return $user->plan ? round($this->price - ($user->plan->plan->day_price * $user->plan->remaining)) : $this->price;
    }
    public function scopePersonal($query)
    {
        $query->where(['account_type' => 'personal']);
    }
    public function scopeCorporate($query)
    {
        $query->where(['account_type' => 'corporate']);
    }
}
