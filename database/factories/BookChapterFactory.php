<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookChapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $book_id = Book::inRandomOrder()->first()->id;
        $book_edition_id = BookEdition::where('book_id', $book_id)->first()->id;
        if(! BookChapter::where(['book_id' => $book_id, 'book_edition_id' => $book_edition_id])->exists()){
            return [
                'book_id' => $book_id,
                'book_edition_id' => $book_edition_id,
                'name' => $this->faker->name,
                'order' => $this->faker->randomNumber(),
                'content' => $this->faker->text(1500),
                'deadline' => $this->faker->dateTimeBetween('+0 days', '+2 years'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        }
    }
}
