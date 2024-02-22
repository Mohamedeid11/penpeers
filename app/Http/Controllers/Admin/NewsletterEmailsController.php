<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterEmails\SendNewsletterEmail;
use App\Http\Requests\Admin\NewsletterEmails\DeleteNewsletterEmail;
use App\Models\NewsletterEmail;
use App\Services\Admin\NewsletterEmailsCrudService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class NewsletterEmailsController extends BaseAdminController
{
    private $newsletterEmailsCrudService;

    /**
     * NewsletterEmailsController constructor.
     * Authorize requests using App\Policies\NewsletterEmailPolicy.
     * @param NewsletterEmailsCrudService $newsletterEmailsCrudService
     */
    public function __construct(NewsletterEmailsCrudService $newsletterEmailsCrudService)
    {
        parent::__construct(NewsletterEmail::class);
        $this->authorizeResource(NewsletterEmail::class);
        $this->newsletterEmailsCrudService = $newsletterEmailsCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $newsletter_emails = $this->newsletterEmailsCrudService->getAllNewsletterEmails();
        return view('admin.newsletter_emails.index', compact('newsletter_emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $newsletter_email = new NewsletterEmail;
        return view('admin.newsletter_emails.send_email-show', compact('newsletter_email'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SendNewsletterEmail $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(SendNewsletterEmail $request)
    {
        $this->newsletterEmailsCrudService->SendNewsletterEmail($request->all());
        return redirect(route('admin.newsletter_emails.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param NewsletterEmail $newsletterEmail
     * @return Response
     */
    public function show(NewsletterEmail $newsletter_email)
    {
        return view('admin.newsletter_emails.send_email-show', compact('newsletter_email'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteNewsletterEmail $request
     * @param NewsletterEmail $newsletterEmail
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteNewsletterEmail $request, NewsletterEmail $newsletterEmail)
    {
        $this->newsletterEmailsCrudService->DeleteNewsletterEmail($newsletterEmail);
        return redirect(route('admin.newsletter_emails.index'));
    }

    /**
     * @param DeleteNewsletterEmail $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteNewsletterEmail $request){
        $this->newsletterEmailsCrudService->batchDeleteNewsletterEmails($request->all());
        return redirect(route('admin.newsletter_emails.index'));
    }

}
