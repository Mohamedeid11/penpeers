<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $profile_pic = ['defaults/Charectores-01.png' , 'defaults/Persons-07.png' ,'defaults/Charectores-04.png' ,'defaults/Charectores-05.png' ,'defaults/Charectores-06.png','defaults/Persons-06.png','defaults/Persons-15.png'];
        
        return [
            'name' => $this->faker->name(),
            "username" => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'country_id'=> Country::inRandomOrder()->first()->id,
            'role_id'=> Role::inRandomOrder()->first()->id,
            'plan_id'=> Plan::inRandomOrder()->first()->id,
            'profile_pic' => $this->faker->randomElement($profile_pic),
            'gender'=> $this->faker->randomElement(['m', 'f']),
            'language'=>'en'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
