<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublishRequests\ApprovePublishRequest;
use App\Http\Requests\Admin\PublishRequests\DeletePublishRequest;
use App\Models\BookPublishRequest;
use App\Services\Admin\publishRequestsCrudService;
use Illuminate\Http\Request;

class PublishRequestsController extends Controller
{
    protected $publishRequestsCrudService;
    public function __construct(publishRequestsCrudService $publishRequestsCrudService)
    {
        $this->publishRequestsCrudService = $publishRequestsCrudService;
    }

    public function index(){
        $this->authorize('viewAny', BookPublishRequest::class);
        $publishRequests = $this->publishRequestsCrudService->getAllpublishRequests();
        return view('admin.publish_requests.index', compact('publishRequests'));
    }

    public function approvePublishRequest(ApprovePublishRequest $request, BookPublishRequest $publishRequest){
        $this->authorize('approve', BookPublishRequest::class);
        $this->publishRequestsCrudService->approvePublishRequest($publishRequest);
        return back()->with('success', 'Publish request approved successfully');
    }

    public function destroy(DeletePublishRequest $request, BookPublishRequest $publishRequest){
        $this->authorize('delete', $publishRequest);
        $this->publishRequestsCrudService->deletebookPublishRequest($publishRequest);
        return back();
    }

    public function batchDestroy(DeletePublishRequest $request){
        $this->publishRequestsCrudService->batchDeletepublishRequests($request->all());
        return back();
    }
}
