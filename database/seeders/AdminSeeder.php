<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        $role = Role::where(['name' => 'admin'])->first();

        $users = [
            [
                'name' => 'islam' ,
                'password' => 'islm9955'
            ] ,
            [
                'name' => 'mohamed' ,
                'password' => 'mohamed'
            ] ,
            [
                'name' => 'nahla' ,
                'password' => 'nhla9988'
            ] ,
            [
                'name' => 'admin' ,
                'password' => 'ali6600'
            ] ,
            [
                'name' => 'harsha' ,
                'password' => 'harsha'
            ] ,
        ];
        foreach ($users as  $user)
        {
            Admin::factory()->create(
                [
                    'name' => ucfirst($user['name']),
                    'username' => $user['name'],
                    'email' => $user['name'] . '@mail.com',
                    'password' => Hash::make($user['password']),
                    'is_system' => true,
                    'role_id' => $role->id,
                ]
            );
        }
    }
}
