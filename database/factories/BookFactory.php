<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        return [
            'title' => $name,
            'description' => $this->faker->text,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'language' => 'en',
            'slug' => Str::slug($name),
            'popular' => false,
            'front_cover' => 'defaults/front.png',
            'back_cover' => 'defaults/back.png',
            'visibility' => $this->faker->randomElement(['public', 'private', 'shared']),
            'completed' => 0,
            'sample' => true,
            'price' => $this->faker->numberBetween(0, 100)
        ];
    }
}
