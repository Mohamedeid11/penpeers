<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name'=>'admin', 'guard'=>'admin', 'display_name'=>'Admin', 'is_system'=>true],
//            ['name'=>'editor', 'guard'=>'admin', 'display_name'=>'Editor', 'is_system'=>true],
            ['name'=>'author', 'guard'=>'user', 'display_name'=>'Author', 'is_system'=>true],
//            ['name'=>'publisher', 'guard'=>'user', 'display_name'=>'Publisher', 'is_system'=>true]
        ]
        );
    }
}
