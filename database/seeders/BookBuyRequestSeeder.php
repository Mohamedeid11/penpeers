<?php

namespace Database\Seeders;

use App\Models\BookBuyRequest;
use Illuminate\Database\Seeder;

class BookBuyRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookBuyRequest::factory()->count(20)->create();
    }
}
