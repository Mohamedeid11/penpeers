<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'Silver',
                'account_type' => 'personal',
                'period' => 'annually',
                'price' => 100,
                'items' => 0,
                'is_system' => true,
            ],
            [
                'name' => 'Gold',
                'account_type' => 'personal',
                'period' => 'triennially',
                'price' => 250,
                'items' => 0,
                'is_system' => false,
            ],
        ];
        DB::table('plans')->insert($plans);
    }
}
