<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_categories')->insert(
            [
                [
                    'guard' => 'admin',
                    'name' => 'countries',
                    'display_name' => 'Countries',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'users',
                    'display_name' => 'Users',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'pages',
                    'display_name' => 'Pages',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'faqs',
                    'display_name' => 'Faqs',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'guides',
                    'display_name' => 'Guides',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'subscriptions',
                    'display_name' => 'Subscriptions',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'newsletter_emails',
                    'display_name' => 'Newsletter Emails',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'settings',
                    'display_name' => 'Settings',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'social_links',
                    'display_name' => 'Social Links',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'contact_messages',
                    'display_name' => 'Contact Messages',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'publish_requests',
                    'display_name' => 'Publish Requests',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'books',
                    'display_name' => 'Books',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'editions',
                    'display_name' => 'Editions',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'occupations',
                    'display_name' => 'Occupations',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'consults',
                    'display_name' => 'Consults',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'blogs',
                    'display_name' => 'Blogs',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'guard' => 'admin',
                    'name' => 'plans',
                    'display_name' => 'Plans',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
