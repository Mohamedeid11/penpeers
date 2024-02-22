<?php

namespace Database\Factories;

use App\Models\Consult;
use App\Models\Occupation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email'=>  $this->faker->email ,
            'phone'=>  $this->faker->phoneNumber ,
            'occupation_id' => Occupation::inRandomOrder()->first()->id
        ];
    }
}
