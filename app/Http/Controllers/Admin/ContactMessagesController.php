<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactMessages\DeleteContactMessage;
use App\Models\ContactMessage;
use App\Services\Admin\ContactMessagesCrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class ContactMessagesController extends BaseAdminController
{
    private $contactMessagesCrudService;

    /**
     * ContactMessagesController constructor.
     * Authorize requests using App\Policies\Admin\ContactMessage.
     * @param ContactMessagesCrudService $contactMessagesCrudService
     */
    public function __construct(ContactMessagesCrudService $contactMessagesCrudService)
    {
        parent::__construct(ContactMessage::class);
        $this->authorizeResource(ContactMessage::class);
        $this->contactMessagesCrudService = $contactMessagesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $contact_messages = $this->contactMessagesCrudService->getAllContactMessages();
        return view('admin.contact_messages.index', compact('contact_messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param ContactMessage $contact_message
     * @return Application|Factory|View|Response
     */
    public function show(ContactMessage $contact_message)
    {
        $this->contactMessagesCrudService->tagRead($contact_message);
        return view('admin.contact_messages.show', compact('contact_message'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteContactMessage $request
     * @param ContactMessage $contactMessage
     * @return Application|RedirectResponse|Response|Redirector
     * @throws \Exception
     */
    public function destroy(DeleteContactMessage $request, ContactMessage $contactMessage)
    {
        $this->contactMessagesCrudService->deleteContactMessage($contactMessage);
        return redirect(route('admin.contact_messages.index'));
    }

    /**
     * @param DeleteContactMessage $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteContactMessage $request){
        $this->contactMessagesCrudService->batchDeleteContactMessages($request->all());
        return redirect(route('admin.contact_messages.index'));
    }
}
