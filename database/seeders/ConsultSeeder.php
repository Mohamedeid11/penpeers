<?php

namespace Database\Seeders;

use App\Models\Consult;
use Illuminate\Database\Seeder;

class ConsultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Consult::factory()->count(20)->create();
    }
}
