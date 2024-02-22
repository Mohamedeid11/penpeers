<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $social_links = [
            [
                'name'=>'Facebook',
                'link'=>'https://www.fb.com',
                'icon'=>'bi bi-facebook',
                'active' => true
            ],
            [
                'name'=>'Twitter',
                'link'=>'https://www.twitter.com',
                'icon'=>'bi bi-twitter',
                'active' => true
            ],
            [
                'name'=>'Youtube',
                'link'=>'https://www.youtube.com',
                'icon'=>'bi bi-youtube',
                'active' => true
            ],

        ];
        DB::table('social_links')->insert($social_links);
    }
}
