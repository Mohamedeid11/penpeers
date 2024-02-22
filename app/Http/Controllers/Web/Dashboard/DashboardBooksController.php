<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Events\DeleteBookFiles;
use App\Events\GenerateBookPDFS;
use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\AddReviewRequest;
use App\Http\Requests\Web\Dashboard\Books\UpdateBookRequest;
use App\Http\Requests\Web\Dashboard\Books\CreateBookRequest;
use App\Mail\InformAuthorsDeletingBook;
use App\Models\Book;
use App\Models\BookReview;
use App\Notifications\RealTimeNotification;
use App\Services\AuthorBooksService;
use App\Services\GeneralWebService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardBooksController extends BaseWebController
{
    protected $authorBooksService;
    protected $generalWebService;

    public function __construct(AuthorBooksService $authorBooksService,  GeneralWebService $generalWebService)
    {
        parent::__construct();
        $this->authorBooksService = $authorBooksService;
        $this->generalWebService = $generalWebService;

        $this->middleware('check_plan_validity', ['except' => ['index',  'contributors', 'show', 'searchAuthors','searchAuthorsByName', 'download_book_pdf', 'create']]);
        $this->middleware('book_completed_actions', ['except' => 'destroy']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index(Request $request)
    {
        $type = $request->type;
        if ($type == "draft") {
            $books = $this->authorBooksService->listAllDraft();
            $title = "My Ongoing Books List";
        } elseif ($type == "completed") {
            $books = $this->authorBooksService->listAllCompleted();
            $title = "My Completed Books List";
        } elseif ($type == "published") {
            $books = $this->authorBooksService->listAllPublished();
            $title = "My Published Books List";
        } elseif ($type == "shown") {
            $books = $this->authorBooksService->listAllShown();
            $title = "My Shown Books List";
        } elseif ($type == "hidden") {
            $books = $this->authorBooksService->listAllHidden();
            $title = "My Hidden Books List";
        } else if ($type == 'redit') {
            $books = $this->authorBooksService->listAllReditingBooks();
            $title = "My Re-editing Books List";
        } else if ($type == 'Participations') {
            $books = $this->authorBooksService->listAllContribution();
            $title = "Me as Co-author";
        } else {
            $books = $this->authorBooksService->listAll();
            $title = "My Books List";
        }

        return view('web.dashboard.books.index', compact('books', 'title'));
    }

    public function contributors(Request $request)
    {
        $contributors = $this->authorBooksService->listAllContributors();

        return view('web.dashboard.books.books-contributors', compact('contributors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $genres = $this->authorBooksService->listGenres();
        return view('web.dashboard.books.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBookRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateBookRequest $request)
    {
        $book = $this->authorBooksService->create($request);
        session()->flash('success',  __('global.Book.created'));
        return redirect(route('web.dashboard.books.editions.special_chapters.index', ['book'=>$book->id, 'edition'=>1]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|Response
     */
    public function show(int $id)
    {
        $book = $this->authorBooksService->getBook($id);
        $edition = $book->editions->first();
        return view('web.dashboard.books.manage', compact('book', 'edition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $book_id
     * @return Application|Factory|View|Response
     */
    public function edit($book_id)
    {
        $book = $this->authorBooksService->getBook($book_id);
        $genres = $this->authorBooksService->listGenres();
        return view('web.dashboard.books.edit', compact('book', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBookRequest $request
     * @param $book_id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(UpdateBookRequest $request, $book_id)
    {
        // dd($request);
        $book = $this->authorBooksService->getBook($book_id);
        $this->authorBooksService->update($request, $book);
        session()->flash('success', __('global.Book.updated'));
        return redirect(route('web.dashboard.books.editions.edition_settings', ['book' => $book->id,  'edition'=>1]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);
//
//        $user = $book->lead_author;
//
//        $email = $user ? $user->email : "";
//
//        Mail::to($email)->send(new DeleteBookApprove($book, $user));
        $book->deleted_at = Carbon::now()->addDays(14);
        $book->update();
        $authors = $book->book_participants->pluck('user');

        foreach ($authors as $author) {
            Mail::to($author->email)->send(new InformAuthorsDeletingBook($book, $author));
            if ($author->id != $book->lead_author->id)
            {
                $url = route('web.dashboard.books.authors.index' ,  $book->id);

                $url_type = 'deleting_book';

                $text = "This book ,<a style='color: #ce7852; text-decoration: underline' href= $url ><strong> $book->title </strong></a> , is set to be deleted on " . $book->deleted_at->toFormattedDateString()  ;

                $author->notify(new RealTimeNotification( $text , $url , $url_type));

            }

        }
        return back()->with('success',__('global.Book.delete_book_success'));
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function searchAuthors(Request $request): Collection
    {
        $search = $request->input('q');
        return $this->authorBooksService->search($search);
    }

    public function searchAuthorsByName(Request $request): Collection
    {
        $search = $request->input('q');
        $interests = $request->input('interests');

        return $this->authorBooksService->searchByName($search, $interests);
    }

    public function delete_book(Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $deleted = $this->authorBooksService->deleteBook($book);
        if ($deleted) {
            session()->flash('success', __('global.Book.success_delete'));
        }

        return redirect(route('web.dashboard.books.index'));
    }
    public function cancel_delete_book(Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $cancel_delete = $this->authorBooksService->cancelDeletingBook($book);

        if ($cancel_delete) {
            return back()->with('success', __('global.Book.success_cancel_delete') );
        }

    }
//    public function download_book_pdf(Book $book)
//    {
//        $dedicationsChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function ($q) {
//            $q->dedications();
//        })->get();
//
//        $acknowledgmentsChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function ($q) {
//            $q->acknowledgments();
//        })->get();
//
//        $introChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function ($q) {
//            $q->intro();
//        })->get();
//
//        $regularChapters = $book->book_chapters()->with('authors')->get();
//
//        $lead_author = $book->book_participants()->leadAuthor($book->id)->get();
//
//        $not_lead_author = $book->book_participants()->notALeadAuthor()->get();
//
//
//        return view('web.partials.book-pdf', compact('book', 'dedicationsChapters', 'acknowledgmentsChapters', 'introChapters', 'regularChapters', 'lead_author', 'not_lead_author'));
//    }

    public function completeBook(Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);

        if (!$book->allChaptersCompleted()) {

            session()->flash('error', __('global.Book.error_complete'));
            return redirect()->back();

        } else {

            $completed = $this->authorBooksService->completeBook($book);
            if ($completed) {
                session()->flash('success', __('global.Book.success_complete'));

                //this one for download the first and last pages

                event(new GenerateBookPDFS($book , 'covered_pages'));
            }

        }

        return redirect()->back();
    }

    public function toggleBookVisibiltyStatus(Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);
        $this->authorBooksService->toggleBookVisibiltyStatus($book);

        if($book->status){

            return back()->with('success', __('global.Book.status_shown'));

        } else {

            return back()->with('success', __('global.Book.status_hidden'));

        }
    }
    public function reditBook(Book $book)
    {
        $book = $this->authorBooksService->getBook($book->id);
        $this->authorBooksService->reditBook($book);

        event(new DeleteBookFiles($book , 'covered_pages'));

        return redirect(route('web.dashboard.books.editions.special_chapters.index', ['book'=>$book->id, 'edition'=> 1]))->with('success', __('global.Book.status_changing_to_reedit'));
    }

    public function bookReview(AddReviewRequest $request, $slug)
    {
        $book = $this->generalWebService->getBookBySlug($slug);
        if ( in_array(auth()->user()->id ,$book->participants->pluck('id')->toArray()) ) {
            return back()->with('error', __('global.Book.error_review_your_book'));
        }

        BookReview::create([

            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'rate'   => $request->rate,
            'review' => $request->review

        ]);

        //send notification to lead author
        $url = route('web.view_book', ['slug' => $book->slug]) ;
        $url_type = 'review_book';
        $book_url = '<strong style="color: #ce7852;">'. $book->title .'</strong>';

        foreach ($book->participants as $author)
        {
            $author->notify(new RealTimeNotification( '<strong style="color: #ce7852;">'. (auth()->user()->name)  .'</strong>'. ' has reviewed your book ' .  $book_url  , $url , $url_type));
        }

        return back()->with('success', __('global.Book.success_review'));
    }
}
