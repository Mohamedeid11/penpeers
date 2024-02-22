<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookBuyRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $known_users = [ 'mohamed' , 'islam' , 'nahla' , 'penpeers' , 'ali' ];
        $user = User::whereIn('username', $known_users)->has('books', '>', 0)->inRandomOrder()->first();

        $book = $user->books()->where('completed' ,1 )->where('price' , '!=' , null)->inRandomOrder()->first();
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' =>$this->faker->phoneNumber,
            'message' => Str::random(100),
            'book_id' => $book->id,
        ];
    }
}
