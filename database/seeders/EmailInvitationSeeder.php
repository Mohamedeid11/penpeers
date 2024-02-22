<?php

namespace Database\Seeders;

use App\Models\EmailInvitation;
use Illuminate\Database\Seeder;

class EmailInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailInvitation::factory()->count(10)->create();
    }
}
