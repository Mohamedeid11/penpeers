<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Events\DeleteBookFiles;
use App\Events\GenerateBookPDFS;
use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Dashboard\Chapters\AddSpecialChapterRequest;
use App\Mail\DeleteSpecialChapterApprove;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookSpecialChapter;
use App\Services\BookSpecialChaptersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardSpecialChaptersController extends BaseWebController
{
    protected $bookSpecialChaptersService;
    public function __construct(BookSpecialChaptersService $bookSpecialChaptersService)
    {
        parent::__construct();
        $this->bookSpecialChaptersService = $bookSpecialChaptersService;
        $this->middleware('check_plan_validity')->except('index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($book_id, $edition_number)
    {
        $book = $this->bookSpecialChaptersService->getBook($book_id);
        $edition = $this->bookSpecialChaptersService->getEdition($book_id, $edition_number);
        // if($edition->status == 1 ){
        //     return redirect(route('web.dashboard.books.editions.edition_settings', ['book' => $book->id, 'edition' => $edition->edition_number]));
        // }
        $special_chapters = $this->bookSpecialChaptersService->getSpecialChapters();
        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }
        return view('web.dashboard.books.introduction', compact('edition', 'book', 'special_chapters','abiltiy_to_add_new_edition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddSpecialChapterRequest $request, $book_id, $edition_number)
    {
        $edition = $this->bookSpecialChaptersService->getEdition($book_id, $edition_number);

        if (BookSpecialChapter::where([
                'book_id' => $book_id,
                'special_chapter_id' => $request->special_chapter_id,
                'book_edition_id' =>  $edition->id
                ])->count() > 0) {
            session()->flash('error',  __('global.special_chapters.error_add') );
            return back();
        }
        $book_special_chapter = $this->bookSpecialChaptersService->addSpecialChapter($request, $book_id, 1);
        session()->flash('success',  __('global.special_chapters.success_add') );
        return redirect(route('web.dashboard.books.editions.special_chapters.edit', ['book'=> $book_id, 'edition'=> 1, 'special_chapter'=> $book_special_chapter->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($book_id, $edition_number, $special_chapter_id)
    {
        $book = $this->bookSpecialChaptersService->getBook($book_id);
        $edition = $this->bookSpecialChaptersService->getEdition($book_id, $edition_number);
        $book_special_chapter = $edition->special_chapters()->where(['id' => $special_chapter_id])->firstOrFail();
        $special_chapters = $this->bookSpecialChaptersService->getSpecialChapters();
        //        return $special_chapter;
        return view('web.dashboard.books.special_chapters.base', compact('book', 'edition', 'book_special_chapter', 'special_chapters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $book_id, $edition_number, $special_chapter_id)
    {
        $this->bookSpecialChaptersService->updateSPecialChapter($request,  $book_id, $edition_number, $special_chapter_id);
        session()->flash('success',  __('global.special_chapters.success_update') );
        return back();
    }
    public function updateContent(Request $request, $book_id, $edition_number, $special_chapter_id)
    {
        $book = $this->bookSpecialChaptersService->getBook($book_id);
        if($book->lead_author->id != auth()->id())
        {
            abort(403);
        }
        $edition = $this->bookSpecialChaptersService->getEdition($book_id, $edition_number);
        $chapter = $edition->special_chapters()->where(['id' => $special_chapter_id])->firstOrFail();
        $chapter->content = $request->get('content');
        $chapter->save();

        return ['status' => 'success'];
    }

    public function chapter_compeleted_email(Book $book, BookEdition $edition, BookSpecialChapter $special_chapter)
    {

        $book = $this->bookSpecialChaptersService->getBook($book->id);
        $edition = $this->bookSpecialChaptersService->getEdition($book->id, $edition->edition_number);



        //Send Email to Lead Author To inform That Chapter Finished
        // $user = Auth::user();
        // $lead_author_id = $book->book_participants()->leadAuthor($book->id)->firstOrFail()->user_id;
        // $lead_author = User::find($lead_author_id);
        // Mail::to($lead_author->email)->send(new InformLeadAuthorSpecialChapterCompleted($book, $edition, $special_chapter, $user,  $lead_author));
        //End Email
        $special_chapter->update([
            'completed' => 1,
            'completed_at' => Now(),
        ]);

        session()->flash('success',  __('global.special_chapters.success_completed') );

        event( new GenerateBookPDFS($book , 'special_chapter') );

        return back();
    }


    public function chapter_not_compeleted_email(Book $book, BookEdition $edition, BookSpecialChapter $special_chapter)
    {
        $book = $this->bookSpecialChaptersService->getBook($book->id);
        $edition = $this->bookSpecialChaptersService->getEdition($book->id, $edition->edition_number);
        //Send Email to CO Author To inform That Chapter Not Finished
        // this action is doing by the lead author , so needed to get the co author of this book  with edition and sepcial chapter id
        // $user = Auth::user();
        // $co_author_id = $book->special_chapters_authors()->where('book_special_chapter_id', $special_chapter->id)->first()->id;
        // $co_author = User::find($co_author_id);
        // Mail::to($co_author->email)->send(new InformCoAuthorSpecialChapterNotCompleted($book, $edition, $special_chapter, $user,  $co_author));
        //End Email
        $special_chapter->update([
            'completed' => 0,
            'completed_at' => Null,

        ]);

        session()->flash('success',  __('global.special_chapters.success_re-edit') );

        //deleting the pdf of introduction
        event( new DeleteBookFiles($book , 'special_chapter') );

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book, BookEdition $edition, BookSpecialChapter $special_chapter)
    {
        $book = $this->bookSpecialChaptersService->getBook($book->id);

        $user = $book->lead_author;

        $email = $user ? $user->email : '';

        Mail::to($email)->send(new DeleteSpecialChapterApprove($book, $user, $special_chapter, $edition));

        return back()->with('success',  __('global.email_sent_successfully') . ' , ' . __('global.special_chapters.email_to_delete') );
    }

    public function delete_special_chapter(Book $book, BookEdition $edition, BookSpecialChapter $special_chapter)
    {
        $book = $this->bookSpecialChaptersService->getBook($book->id);

        $delete = $special_chapter->delete();

        if ($delete) {
            session()->flash('success',  __('global.special_chapters.success_delete'));
        } else {
            session()->flash('error',  __('global.special_chapters.error_delete'));
        }

        return redirect(route('web.dashboard.books.editions.special_chapters.index', ['book' => $book->id, 'edition' => 1]));
    }
}
