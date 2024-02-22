<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('special_chapters')->insert([
            ['name'=>"intro", 'display_name'=>"Introduction"],
            // ['name'=>"acknowledgments", 'display_name'=>"Acknowledgments"],
            // ['name'=>"dedications", 'display_name'=>"Dedications"],
        ]);
    }
}
