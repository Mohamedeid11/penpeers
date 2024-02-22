<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            [
                'name' => 'abdallah',
                'email' => 'abdallah@gmail.com',
                'profile_pic' => 'defaults/Charectores-03.png',
                'password' => 'abdallah',
                "bio" => "I have received starred reviews in Future Mark, Library Journal, and Booklist. I'm a Garden Partyand bestseller and a RITA® winner."
            ],
            [
                'name' => 'ibrahim',
                'email' => 'memoeid1996@gmail.com',
                'profile_pic' => 'defaults/Persons-14.png',
                'password' => 'mohamed',
                "bio" => "I'm the Decorators’ Digest bestselling author of They Both Die at the End, More Happy Than Not, and History Is All You Left Me and—together with Becky Albertalli—coauthor of What If It’s Us. I was named a Publishers Weekly Flying Start."
            ],
            [
                'name' => 'ahmed',
                'email' => 'penpeers@gmail.com',
                'profile_pic' => 'defaults/Persons-12.png',
                'password' => 'penpeers',
                "bio" => "I write books about carriages, corsets, and smartwatches. My books have received starred reviews in Publishers Weekly, Library Journal, and Booklist. I'm a What’s New Bestseller."
            ],
            [
                'name' => 'ali',
                'email' => 'ali@gmail.com',
                'profile_pic' => 'defaults/Persons-04.png',
                'password' => 'ali',
                "bio" => "I'm the #1 City Scoop and internationally bestselling author of the Throne of Glass, Court of Thorns and Roses, and Crescent City series. My books have sold millions of copies and are published in thirty-seven languages."
            ],
            [
                'name' => 'mohamed',
                'email' => 'm.mohamedeid11@gmail.com',
                'profile_pic' => 'defaults/Persons-02.png',
                'password' => 'mohamed',
                "bio" => "I grew up in Venice, California, the second son of a Vietnam veteran turned policeman. Initially focusing on performing arts, I attended the prestigious Alexander Hamilton Academy in Los Angeles."
            ],
            [
                'name' => 'islam',
                'email' => 'deveislam95@gmail.com',
                'profile_pic' => 'defaults/Persons-09.png',
                'password' => 'islam',
                "bio" => "I have been writing since childhood, when my mother gave me a lined notebook in which to write down my stories. "
            ],
            [
                'name' => 'nahla',
                'email' => 'nahlaglal@gmail.com',
                'profile_pic' => 'defaults/Persons-15.png',
                'password' => 'nahla',
                "bio" => "I’m a Auntie’s Recipes bestselling author of swashbuckling action-adventure romance. I’m the wife of a rock star, and the mother of two young adults, but I’ve also been a ballerina, a typographer, a film composer, a piano player, a singer in an all-girl rock band, and a voice in those violent video games you won’t let your kids play."
            ],
            [
                'name' => 'hassan',
                'email' => 'hassan@gmail.com',
                'profile_pic' => 'defaults/Persons-10.png',
                'password' => 'hassan',
                "bio" => "I’m retired from the Marine Corps, spending time both as enlisted and as an officer. Then I went to law school, took my law degree into business consulting where I became a business diagnostics specialist and leadership coach. I retired from that at age 52 because I was away from home way too much. That’s when I started writing full time, and I have not looked back since."
            ],
            [
                'name' => 'denis',
                'email' => 'denis.costello@walkingsafarisofsouthafrica.com',
                'profile_pic' => 'defaults/Persons-03.png',
                'password' => 'denis',
                "bio" => "I'm the Huckle & Elm and Distillatebestselling author of more than 25 novels, and the EMMY® award winning co-host of the literary TV show A WORD ON WORDS. I also write urban fantasy under the pen name Joss Walker. "
            ],
        ];
        $role_id = Role::where(['name'=>'author'])->first()->id;

        $plan = Plan::inRandomOrder()->first();


        foreach($users as $i => $one)
        {

            $user = User::factory()->create(
                        [
                            'role_id'=> $role_id,
                            'username' => ($one['name'] == 'ahmed') ? 'penpeers' : $one['name'],
                            'email' => $one['email'],
                            'profile_pic' => $one['profile_pic'],
                            'name' => ucfirst($one['name']),
                            'password' => Hash::make($one['password']),
                            "bio" => $one['bio']
                        ]
                    );

            if($i != 0)
            {

                $payment = Payment::factory()->create(
                    [
                        'user_id'=> $user['id'],
                        'payment_type'=> 'credit',
                        'value' => $plan->price,
                        'confirmed' => true,
                    ]);

                $user->user_plans()->create(['plan_id' => $plan->id, 'payment_id' => $payment['id'], 'start_date' => Carbon::now(), 'end_date' => Carbon::now()->addYears($plan->years)]);

            }
        }


        // User::factory()->count(5)->create(['role_id'=> $role_id]);
    }
}
