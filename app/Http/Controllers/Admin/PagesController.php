<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\CreatePage;
use App\Http\Requests\Admin\Pages\DeletePage;
use App\Http\Requests\Admin\Pages\EditPage;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Services\Admin\PagesCrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class PagesController extends BaseAdminController
{
    private $pagesCrudService;

    /**
     * PagesController constructor.
     * Authorize requests using App\Policies\Admin\Page.
     * @param PagesCrudService $pagesCrudService
     */
    public function __construct(PagesCrudService $pagesCrudService)
    {
        // parent::__construct(Page::class);
        $this->authorizeResource(Page::class);
        $this->pagesCrudService = $pagesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $pages = $this->pagesCrudService->getAllPages();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $page = new Page;
        return view('admin.pages.create-edit', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePage $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreatePage $request)
    {
        $this->pagesCrudService->createPage($request->all());
        return redirect(route('admin.pages.index'));
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
     * @param Page $page
     * @return Application|Factory|View|Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.create-edit', compact('page'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditPage $request
     * @param Page $page
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditPage $request, Page $page)
    {
        $this->pagesCrudService->updatePage($page, $request->all());
        return redirect(route('admin.pages.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeletePage $request
     * @param Page $page
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeletePage $request, Page $page)
    {
        $this->pagesCrudService->deletePage($page);
        return redirect(route('admin.pages.index'));
    }

    /**
     * @param DeletePage $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeletePage $request){
        $this->pagesCrudService->batchDeletePages($request->all());
        return redirect(route('admin.pages.index'));
    }
}
