<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Traits\HasAdminForm;

class Payment extends Model
{

    use HasFactory, HasAdminForm;
    protected $fillable = ['user_id', 'payment_type', 'transaction_id', 'value', 'confirmed'];
    protected $casts = [
        'confirmed' => 'boolean'
    ];
    protected $appends = [
        'remaining'
    ];
    protected function form_fields()
    {
        $plans = Plan::all();
        $choices = [];
        foreach ($plans as $plan) {
            $choices[$plan->id] = __('global.' . $plan->account_type) . " - " . __('global.' . $plan->name) . " - " .__('global.' . $plan->period) . " - " . $plan->price . " USD";
        }
        return collect([
            'plan_id' => collect(['type' => 'select', 'required' => 3, 'choices' => $choices]),
            'payment_type' => collect(['type' => 'select', 'required' => 3, 'choices' => ['credit' => __('admin.credit_card'), 'bank' => __('admin.bank_transfer')]]),
            'value' => collect(['value' => 'number', 'required' => 3]),
            'confirmed' => collect(['type' => 'checkbox']),
        ]);
    }
    public function getRemainingAttribute()
    {
        $now = Carbon::now();
        return new Carbon($this->end_date) >= $now ? (new Carbon($this->end_date))->diffInDays($now) : -1;
    }
}
