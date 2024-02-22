<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\CreateRole;
use App\Http\Requests\Admin\Roles\DeleteRole;
use App\Http\Requests\Admin\Roles\EditRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * RolesController constructor.
     * Authorize requests using App\Policies\Admin\RolePolicy
     */
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roles = Role::get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $role = new Role;
        return view('admin.roles.create-edit', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRole $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRole $request)
    {
        $permissions_ids = $request->filled('permissions') ? $request->get('permissions') : [];
        $wrong_permission = Permission::whereIn('id', $permissions_ids)->whereHas('permission_category', function (Builder $query ){
            $query->where('guard', '!=', 'admin');
        })->first();
        if($wrong_permission){
            return back()->withInput()->withErrors(['permissions'=>__('admin.wrong_permissions')]);
        }
        $data =  $request->only(['name', 'display_name', 'guard']);
        $role = Role::create($data);
        if ($request->get('translations'))
        {
            $role->add_translations($request->get('translations'));
        }
        $role->permissions()->attach($permissions_ids);
        $request->session()->flash('success', __('admin.success_add', ['thing'=>__('global.role')]));
        return redirect(route('admin.roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Role $role)
    {
        return view('admin.roles.create-edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditRole $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EditRole $request, Role $role)
    {
        $permissions_ids = $request->filled('permissions') ? $request->get('permissions') : [];
        $wrong_permission = Permission::whereIn('id', $permissions_ids)->whereHas('permission_category', function (Builder $query ){
            $query->where('guard', '!=', 'admin');
        })->first();
        if($wrong_permission){
            return back()->withInput()->withErrors(['permissions'=>__('admin.wrong_permissions')]);
        }
        $data =  $request->only(['name', 'display_name', 'guard']);
        $role->update($data);
        if ($request->get('translations'))
        {
            $role->add_translations($request->get('translations'));
        }
        $role->permissions()->detach($role->permissions()->pluck('permissions.id'));
        $role->permissions()->attach($permissions_ids);
        $request->session()->flash('success', __('admin.success_edit', ['thing'=>__('global.role')]) );
        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRole $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(DeleteRole $request, Role $role)
    {
        $role->delete();
        $request->session()->flash('success', __('admin.success_delete', ['thing'=>__('global.role')]) );
        return redirect(route('admin.roles.index'));
    }

    /**
     *  Batch remove specified resources from storage
     *
     * @param DeleteRole $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteRole $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $wrong_role = Role::whereIn('id', $ids)->where(['is_system'=>true])->first();
        if($wrong_role){
            $request->session()->flash('error', __('admin.no_delete_sys_roles') );
            return redirect(route('admin.roles.index'));
        }
        Role::whereIn('id', $ids)->delete();
        $request->session()->flash('success', __('admin.success_delete', ['thing'=>__('global.role')]) );
        return redirect(route('admin.roles.index'));
    }
}
