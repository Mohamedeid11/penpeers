<?php

namespace Database\Factories;

use App\Models\SpecialChapter;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecialChapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'display_name' =>$this->faker->name,
        ];
    }
}
