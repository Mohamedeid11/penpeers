<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guides\CreateGuide;
use App\Http\Requests\Admin\Guides\DeleteGuide;
use App\Http\Requests\Admin\Guides\EditGuide;
use App\Models\Guide;
use App\Services\Admin\GuidesCrudService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class GuidesController extends BaseAdminController
{
    private $guidesCrudService;

    /**
     * GuidesController constructor.
     * Authorize requests using App\Policies\GuidePolicy.
     * @param GuidesCrudService $guidesCrudService
     */
    public function __construct(GuidesCrudService $guidesCrudService)
    {
        parent::__construct(Guide::class);
        $this->authorizeResource(Guide::class);
        $this->guidesCrudService = $guidesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $guides = $this->guidesCrudService->getAllGuides();
        return view('admin.guides.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $guide = new Guide;
        return view('admin.guides.create-edit', compact('guide'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateGuide $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateGuide $request)
    {
        $this->guidesCrudService->CreateGuide($request->all());
        return redirect(route('admin.guides.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Guide $guide
     * @return Application|Factory|View|Response
     */
    public function edit(Guide $guide)
    {
        return view('admin.guides.create-edit', compact('guide'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditGuide $request
     * @param Guide $guide
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditGuide $request, Guide $guide)
    {
        $this->guidesCrudService->updateGuide($guide, $request->all());
        return redirect(route('admin.guides.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteGuide $request
     * @param Guide $guide
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteGuide $request, Guide $guide)
    {
        $this->guidesCrudService->DeleteGuide($guide);
        return redirect(route('admin.guides.index'));
    }

    /**
     * @param DeleteGuide $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteGuide $request){
        $this->guidesCrudService->batchDeleteGuides($request->all());
        return redirect(route('admin.guides.index'));
    }

}
