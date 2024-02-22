<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SocialLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'link' => $this->faker->url,
            'icon' => $this->faker->randomElement(['fa fa-facebook', 'fa fa-twitter', 'fa fa-instagram']),
            'active' => $this->faker->randomElement([true, false])
        ];
    }
}
