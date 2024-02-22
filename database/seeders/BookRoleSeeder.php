<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('book_roles')->insert([
            ['name' => "lead_author", 'display_name' => "Lead Author"],
            ['name' => "co_author", 'display_name' => "Co Author"],
        ]);
    }
}
