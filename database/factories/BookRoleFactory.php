<?php

namespace Database\Factories;

use App\Models\BookRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'display_name' => $this->faker->name,
        ];
    }
}
