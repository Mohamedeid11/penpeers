<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'book_id'=> Book::where('status',1)->where('completed',1)->first()->id,
          'user_id' => User::inRandomOrder()->first()->id,
          'rate' => $this->faker->numberBetween(1,5),
          'review' => $this->faker->text(500),
        ];
    }
}
