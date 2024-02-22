<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookEditionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookEdition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        return [
            'book_id' => Book::inRandomOrder()->first()->id,
            'title' => $name,
            'description' => $this->faker->text,
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'language' => 'en',
            'front_cover' => 'defaults/front.png',
            'back_cover' => 'defaults/back.png',
            'edition_number' => $this->faker->randomNumber(),
            'original_price'=>100,
            'discount_price'=>50
        ];
    }
}
