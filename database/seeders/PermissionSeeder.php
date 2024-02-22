<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_categories = PermissionCategory::all();
        $permissions = [];
        foreach ($permission_categories as $permission_category){
            if ($permission_category->name == 'settings'){
                $types = [['view', 'View'], ['edit', 'Edit']];
            } else if($permission_category->name == 'newsletter_emails'){
                $types = [['view', 'View'], ['send', 'Send'], ['delete', 'Delete']];
            } else if($permission_category->name == 'publish_requests'){
                $types = [['view', 'View'], ['approve', 'Approve'],['delete', 'Delete']];
            } else if($permission_category->name == 'blogs'){
                $types = [['view', 'View'], ['add', 'Add'],['edit', 'Edit'], ['delete', 'Delete'], ['translate', 'Translate'], ['approve', 'Approve']];
            } else if($permission_category->name == 'plans'){
                $types = [['view', 'View'], ['add', 'Add'],['edit', 'Edit'], ['delete', 'Delete']];
            } else {
                $types = [['view', 'View'], ['add', 'Add'],['edit', 'Edit'], ['delete', 'Delete'], ['translate', 'Translate']];
            }
            foreach ($types as $type){
                array_push($permissions, [
                    'name' => $type[0].'_'.$permission_category->name,
                    'display_name' => $type[1].' '.$permission_category->display_name,
                    'permission_category_id' => $permission_category->id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
        DB::table('permissions')->insert($permissions);
    }
}
