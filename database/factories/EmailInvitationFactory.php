<?php

namespace Database\Factories;

use App\Models\BookRole;
use App\Models\EmailInvitation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $known_users = [ 'mohamed' , 'islam' , 'nahla' , 'penpeers' , 'ali' ];

        $user = User::whereIn('username', $known_users)->has('books', '>', 0)->inRandomOrder()->first();
        $book = $user->books()->first();
        $status = $this->faker->randomElement(['0', '1', '2']);
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'invited_by' => $user->id,
            'book_id' => $book->id,
            'invited_at' => Carbon::now(),
            'answered_at' => ($status == '0') ? Null : Carbon::now()->addMinutes(60),
            'status' => $status ,
            'book_role_id' => BookRole::inRandomOrder()->first()->id
        ];
    }
}
