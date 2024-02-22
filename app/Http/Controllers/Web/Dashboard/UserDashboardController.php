<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\BaseWebController;
use App\Services\AuthorBooksService;
use App\Services\BlogsService;
use Illuminate\Http\Request;

class UserDashboardController extends BaseWebController
{
    protected $authorBooksService;
    protected $blogsService;

    public function __construct(AuthorBooksService $authorBooksService, BlogsService $blogsService)
    {
        parent::__construct();
        $this->authorBooksService = $authorBooksService;
        $this->blogsService = $blogsService;
    }

    public function index()
    {
        $books = $this->authorBooksService->listAll();

        $draft = $this->authorBooksService->listAllDraft()->count();

        $completed = $this->authorBooksService->listAllCompleted()->count();

        $published =  $this->authorBooksService->listAllPublished()->count();

        $reditingBooks = $this->authorBooksService->listAllReditingBooks()->count();

        $shownBooks =  $this->authorBooksService->listAllShown()->count();

        $hiddenBooks =  $this->authorBooksService->listAllHidden()->count();

        $contributors = $this->authorBooksService->listAllContributors()->count();

        $contribution = $this->authorBooksService->listAllContribution()->count();

        $receivedInvitations = $this->authorBooksService->getAllUserReceivedInvitations()->count();

        $registeredAuthorsInvitations = $this->authorBooksService->getAllRegisteredAuthorsInvitations()->count();

        $emailInvitations = $this->authorBooksService->getAllEmailInvitations()->count();

        $blogs = $this->blogsService->getAllPosts()->count();

        return view('web.dashboard.index', compact('draft', 'completed', 'published', 'contribution', 'contributors', 'books', 'blogs', 'receivedInvitations', 'shownBooks', 'hiddenBooks', 'reditingBooks', 'registeredAuthorsInvitations', 'emailInvitations'));
    }
}
