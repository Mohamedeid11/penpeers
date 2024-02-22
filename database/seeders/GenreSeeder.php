<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            ['name'=>"History"],
            ['name'=>"Science"],
            ['name'=>"Science Fiction"],
            ['name'=>"Technology"],
            ['name'=>"Literature"],
            ['name'=>"Mystery"],
            ['name'=>"Romance"],
            ['name'=>"Children's Literature"],
            ['name'=>"Sport"],
            ['name'=>"Music"],
            ['name'=>"Art"],
        ]);
    }
}
