<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Countries\DeleteCountry;
use App\Http\Requests\Admin\SocialLinks\CreateSocialLink;
use App\Http\Requests\Admin\SocialLinks\DeleteSocialLink;
use App\Http\Requests\Admin\SocialLinks\EditSocialLink;
use App\Models\Country;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialLinksController extends BaseAdminController
{
    /**
     * SocialLinksController constructor.
     * Authorize requests using App\Policies\Admin\SocialLink.
     */
    public function __construct()
    {
        parent::__construct(SocialLink::class);
        $this->authorizeResource(SocialLink::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $social_links = SocialLink::paginate(100);
        return view('admin.social_links.index', compact('social_links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $social_link = new SocialLink();
        return view('admin.social_links.create-edit', compact('social_link'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSocialLink $request)
    {
        $social_link = SocialLink::create($request->only(['name', 'link', 'icon']));
        $request->session()->flash('success', __('admin.success_add', ['thing'=>__('global.social_link')]) );
        return redirect(route('admin.social_links.index'));
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
     * @param EditSocialLink $request
     * @param SocialLink $social_link
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialLink $social_link)
    {
        return view('admin.social_links.create-edit', compact('social_link'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditSocialLink $request, SocialLink $social_link)
    {
        $social_link->update($request->only(['name', 'link', 'icon']));
        $request->session()->flash('success', __('admin.success_edit', ['thing'=>__('global.social_link')]) );
        return redirect(route('admin.social_links.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteSocialLink $request
     * @param SocialLink $social_link
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteSocialLink $request, SocialLink $social_link)
    {
        $social_link->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.social_link')]) );
        return redirect(route('admin.social_links.index'));
    }
    public function batchDestroy(DeleteSocialLink $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_social_links= SocialLink::whereIn('id', $ids);
        $target_social_links->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.social_link')]) );
        return redirect(route('admin.social_links.index'));
    }
    /**
     *
     * @param $social_link_id
     * @return false|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggle_active($social_link_id){
        $this->authorize('viewAny', SocialLink::class);
        $social_link = SocialLink::findOrFail($social_link_id);
        $social_link->active = !$social_link->active;
        $social_link->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }
}
