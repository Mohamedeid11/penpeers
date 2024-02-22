<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Database\Seeder;

class UserInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach($users as $user){
            UserInterest::factory()->count(2)->sequence(
                        [
                            'user_id' => $user->id,
                            'interest_id' => Interest::inRandomOrder()->first()->id,
                        ],
                        [
                            'user_id' => $user->id,
                            'interest_id' => Interest::inRandomOrder()->first()->id,
                        ]
                )->create();
        }
       

    }
}
