<?php

namespace App\Services\Admin;

use App\Mail\ApproveEditionInform;
use App\Models\BookEdition;
use App\Models\BookPublishRequest;
use App\Models\User;
use App\Repositories\Interfaces\BookPublishRequestRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class publishRequestsCrudService
{
    private $bookPublishRequestRepository;
    public function __construct(BookPublishRequestRepositoryInterface $bookPublishRequestRepository)
    {
        $this->bookPublishRequestRepository = $bookPublishRequestRepository;
    }

    public function getAllpublishRequests()
    {
        return $this->bookPublishRequestRepository->paginate(100);
    }

    public function approvePublishRequest(BookPublishRequest $bookPublishRequest)
    {
        $bookPublishRequest->edition()->update(['status_changed_at' => date('Y-m-d') , 'status' => 1]);
        $bookPublishRequest->update(['approved' => true]);
        //Send EMail To the Lead outhor of Edition that his edition approved
        $user = User::find($bookPublishRequest->user_id);
        $email = $user ? $user->email : '';
        Mail::to($email)->send(new ApproveEditionInform($bookPublishRequest));
    }

    public function deletebookPublishRequest(BookPublishRequest $bookPublishRequest)
    {
        $bookPublishRequest->delete();
        session()->flash('success',  __('admin.success_delete', ['thing' => __('global.publish_request')]));
    }

    public function batchDeletepublishRequests(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->bookPublishRequestRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing' => __('global.publish_requests')]));
    }
}
