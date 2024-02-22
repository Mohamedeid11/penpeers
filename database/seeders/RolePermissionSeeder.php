<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where(['name'=>'admin'])->where(['is_system'=>true])->where(['guard'=>'admin'])->first();
        $admin_permissions = Permission::whereHas('permission_category', function (Builder $query){$query->where(['guard'=>'admin']);})->get();
        $permissions = [];
        foreach($admin_permissions as $permission){
            array_push($permissions, [
                'role_id' => $admin_role->id,
                'permission_id' => $permission->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        DB::table('role_permissions')->insert($permissions);
    }
}
