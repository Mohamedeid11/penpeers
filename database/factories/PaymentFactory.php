<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id'=> User::inRandomOrder()->first()->id,
            "transaction_id" => 'FAKE' . '-' .  date('Y-m-d h:i:s'),
            'payment_type'=> 'credit',
            'value' => Plan::inRandomOrder()->first()->price,
            'confirmed' => true,
        ];
    }
}
