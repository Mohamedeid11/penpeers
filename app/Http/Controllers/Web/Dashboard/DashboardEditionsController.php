<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Dashboard\Editions\AddEditionRequest;
use App\Mail\DeleteEditionApprove;
use App\Models\Book;
use App\Models\BookEdition;
use App\Services\AuthorBooksService;
use App\Services\BookEditionsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Firebase\JWT\JWT;

class DashboardEditionsController extends BaseWebController
{
    protected $bookEditionsService;
    protected $authorBooksService;
    public function __construct(BookEditionsService $bookEditionsService, AuthorBooksService $authorBooksService)
    {
        parent::__construct();
        $this->bookEditionsService = $bookEditionsService;
        $this->authorBooksService = $authorBooksService;
        $this->middleware('check_plan_validity')->except(['index' , 'show' , 'publishEdition' , 'editionSettings']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $book = $this->bookEditionsService->getBook(request()->book);
        $edition = $this->bookEditionsService->getEdition($book->id, 1);
        $special_chapters = $this->bookEditionsService->getSpecialChapters();

        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1 ) {
            $abiltiy_to_add_new_edition = 0;
        }

        return view('web.dashboard.books.introduction', compact('book', 'edition', 'abiltiy_to_add_new_edition', 'special_chapters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Book $book)
    {
        $genres = $this->authorBooksService->listGenres();
        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }

        return view('web.dashboard.books.editions.create', compact('genres', 'book', 'abiltiy_to_add_new_edition'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddEditionRequest $request, Book $book)
    {
        if ($book->editions->last()->status == 1) {
            $edition = $this->bookEditionsService->addEdition($request, $book->id);
            session()->flash('success', __('global.Edition.success_add'));
        }else{
            session()->flash('error', __('global.Edition.not_published'));
        }

        return redirect(route('web.dashboard.books.editions.index', ['book' => $book->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $edition_number)
    {
        $book = $this->bookEditionsService->getBook($id);
        $edition = $this->bookEditionsService->getEdition($id, $edition_number);
        $special_chapters = $this->bookEditionsService->getSpecialChapters();
        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }

        return view('web.dashboard.books.introduction', compact('edition', 'book', 'special_chapters', 'abiltiy_to_add_new_edition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $edition_number)
    {
        $book = $this->bookEditionsService->getBook($id);
        $edition = $this->bookEditionsService->getEdition($id, $edition_number);

        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }

        return view('web.dashboard.books.editions.edit', compact('edition', 'book', 'abiltiy_to_add_new_edition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book, BookEdition $edition)
    {
        $this->bookEditionsService->UpdateEdition($request, $edition);
        return back()->with('success', __('global.Edition.success_update'));
    }

    /**
     * Send Email To remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book, BookEdition $edition)
    {
        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition->edition_number);

        if ($edition->edition_number == 1) {
            session()->flash('error', __('global.Edition.error_delete'));
            return back();
        }

        // $user = Auth::user();
        // $email = $user->email;
        $user = $book->lead_author;
        $email = $user ? $user->email : "";
        Mail::to($email)->send(new DeleteEditionApprove($book, $edition, $user));
        return back()->with('success',  __('global.email_sent_successfully') . ' , ' . __('global.Edition.email_to_delete'));
    }

    public function publishEdition(Book $book, BookEdition $edition)
    {
        // if (!Gate::allows('publish-edition', [$book, $edition]) || ! $book->completed) {
        //     abort(404);
        // }

        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition->edition_number);

        $result =  $this->bookEditionsService->publishEdition($book, $edition);

        if($result){
            return back()->with('success', __('global.Edition.success_complete'));
        }else{
            return back()->with('error', __('global.Edition.error_complete'));
        }

    }

    public function reeditEdition(Book $book, BookEdition $edition)
    {
        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition->edition_number);

        $result = $this->bookEditionsService->reeditEdition($book, $edition);

        if($result){
            return back()->with('success', __('global.Edition.success_re-edit'));
        }else{
            return back()->with('error', __('global.Edition.error_re-edit'));
        }
    }


    public function toggle(Book $book, BookEdition $edition)
    {
        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition->edition_number);

        $this->bookEditionsService->toggleEdition($edition);
        return back()->with('success', __('global.Edition.status'));
    }
    public function delete_edition(Book $book, BookEdition $edition)
    {
        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition->edition_number);

        $this->bookEditionsService->deleteEdition($edition);
        session()->flash('success', __('global.Edition.success_delete'));
        return redirect(route('web.dashboard.books.editions.index', ['book' => $book]));
    }

    public function editionSettings(Book $book, $edition_number)
    {
        $book = $this->bookEditionsService->getBook($book->id);
        $edition = $this->bookEditionsService->getEdition($book->id, $edition_number);

        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }
        $accessKey = env('AccessKey');
        $environmentId = env('EnvironmentId');
        $payload = [
            'aud' => $environmentId,
            'iat' => time(),
        ];

        $token = JWT::encode($payload, $accessKey, 'HS256');

        return view('web.dashboard.books.book_settings', compact('token','edition', 'book', 'abiltiy_to_add_new_edition'));
    }
}
