<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admins\CreateAdmin;
use App\Http\Requests\Admin\Admins\DeleteAdmin;
use App\Http\Requests\Admin\Admins\EditAdmin;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * AdminsController constructor.
     * Authorize requests using App\Policies\Admin\AdminPolicy.
     */
    public function __construct()
    {
        $this->authorizeResource(Admin::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $admins = Admin::get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $admin = new Admin;
        return view('admin.admins.create-edit', compact('admin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdmin $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateAdmin $request)
    {
        $role_id = Role::where('name','admin')->first()->id;
        if (!$role_id){
            return back()->withInput()->withErrors(['role_id'=> __('admin.bad_role')]);
        }
                $data = $request->only(['name', 'username', 'email']);
        $admin = Admin::create(array_merge($data, ['password'=>Hash::make($request->password) ,'role_id' => $role_id]));

        if ($request->input('translations'))
        {
            $admin->add_translations($request->get('translations'));
        }
        $request->session()->flash('success', __('admin.success_add', ['thing'=>__('global.admin')]) );
        return redirect(route('admin.admins.index'));
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
     * @param Admin $admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.create-edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditAdmin $request
     * @param Admin $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EditAdmin $request, Admin $admin)
    {
        $role_id = Role::where('name','admin')->first()->id;
        if (!$role_id){
            return back()->withInput()->withErrors(['role_id'=> __('admin.bad_role')]);
        }
        $data = $request->only(['name', 'username', 'email', 'role_id']);
        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }
        $admin->update(array_merge($data , ['role_id' => $role_id]));
        if ($request->get('translations'))
        {
            $admin->add_translations($request->get('translations'));
        }
        $request->session()->flash('success',  __('admin.success_edit', ['thing'=>__('global.admin')]) );
        return redirect(route('admin.admins.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteAdmin $request
     * @param Admin $admin
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(DeleteAdmin $request, Admin $admin)
    {
        $admin->delete();
        $request->session()->flash('success', __('admin.success_delete', ['thing'=>__('global.admin')]) );
        return redirect(route('admin.admins.index'));
    }

    /**
     * Batch remove specified resources from storage
     *
     * @param DeleteAdmin $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteAdmin $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $user = Auth::guard('admin')->user();
        $admins = Admin::whereIn('id', $ids);
        $self_included = $admins->where(['id' => $user->id])->first();
        if($self_included){
            $request->session()->flash('error', __('admin.cannot_delete_yourself'));
            return redirect(route('admin.admins.index'));
        }
        $system_included = $admins->where(['is_system'=>true])->first();
        if($system_included){
            $request->session()->flash('error', __('admin.no_delete_sys_admins') );
            return redirect(route('admin.admins.index'));
        }
        $admins->delete();
        $request->session()->flash('success', __('admin.success_delete', ['thing'=>__('global.admin')]) );
        return redirect(route('admin.admins.index'));
    }
}
