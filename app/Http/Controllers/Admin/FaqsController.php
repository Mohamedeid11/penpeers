<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faqs\CreateFaq;
use App\Http\Requests\Admin\Faqs\DeleteFaq;
use App\Http\Requests\Admin\Faqs\EditFaq;
use App\Models\Faq;
use App\Services\Admin\FaqsCrudService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class FaqsController extends BaseAdminController
{
    private $faqsCrudService;

    /**
     * FaqsController constructor.
     * Authorize requests using App\Policies\Admin\FaqPolicy.
     * @param FaqsCrudService $faqsCrudService
     */
    public function __construct(FaqsCrudService $faqsCrudService)
    {
        parent::__construct(Faq::class);
        $this->authorizeResource(Faq::class);
        $this->faqsCrudService = $faqsCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $faqs = $this->faqsCrudService->getAllFaqs();
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $faq = new Faq;
        return view('admin.faqs.create-edit', compact('faq'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateFaq $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateFaq $request)
    {
        $this->faqsCrudService->CreateFaq($request->all());
        return redirect(route('admin.faqs.index'));
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
     * @param Faq $faq
     * @return Application|Factory|View|Response
     */
    public function edit(Faq $faq)
    {
        return view('admin.faqs.create-edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditFaq $request
     * @param Faq $faq
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditFaq $request, Faq $faq)
    {
        $this->faqsCrudService->updateFaq($faq, $request->all());
        return redirect(route('admin.faqs.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteFaq $request
     * @param Faq $faq
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteFaq $request, Faq $faq)
    {
        $this->faqsCrudService->DeleteFaq($faq);
        return redirect(route('admin.faqs.index'));
    }

    /**
     * @param DeleteFaq $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteFaq $request){
        $this->faqsCrudService->batchDeleteFaqs($request->all());
        return redirect(route('admin.faqs.index'));
    }

}
