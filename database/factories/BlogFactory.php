<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'image' => $this->faker->randomElement(['defaults/blog-a.png', 'defaults/blog.jpg', 'defaults/blog-b.jpg']),
            "approved" => true,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ];
    }
}
