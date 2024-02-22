<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Events\DeleteBookFiles;
use App\Events\GenerateBookPDFS;
use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Dashboard\Chapters\AddChapterRequest;
use App\Http\Requests\Web\Dashboard\Chapters\UpdateChapterRequest;
use App\Mail\DeleteChapterApprove;
use App\Mail\InformCoAuthorChapterNotCompleted;
use App\Mail\InformLeadAuthorChapterCompleted;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\BookChaptersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardChaptersController extends BaseWebController
{
    protected $bookChaptersService;
    public function __construct(BookChaptersService $bookChaptersService)
    {
        parent::__construct();
        $this->bookChaptersService = $bookChaptersService;
        $this->middleware('check_plan_validity')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($book_id, $edition_num)
    {
        auth()->user()->unreadNotifications()->where(function($query){
                                                                $query->orWhere('data', 'LIKE', '%"url_type":"chapter_completed"%')
                                                                      ->orWhere('data', 'LIKE', '%"url_type":"chapter_not_completed"%'); })->delete();

        $book = $this->bookChaptersService->getBook($book_id);
        $edition = $this->bookChaptersService->getEdition($book_id, $edition_num);
        $abiltiy_to_add_new_edition = 1;
        if ($book->editions->last()->status != 1) {
            $abiltiy_to_add_new_edition = 0;
        }

        return view('web.dashboard.books.chapters', compact('edition', 'book', 'abiltiy_to_add_new_edition'));
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
    public function store(AddChapterRequest $request, $book_id, $edition_number)
    {
        $this->bookChaptersService->addChapter($request, $book_id, $edition_number);
        session()->flash('success',  __('global.chapters.success_add'));
        return back();
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
    public function edit($book_id, $edition_number, $chapter_id)
    {
        $book = $this->bookChaptersService->getBook($book_id);
        $edition = $this->bookChaptersService->getEdition($book_id, $edition_number);
        $chapter = $edition->chapters()->where(['id' => $chapter_id])->firstOrFail();
        return view('web.dashboard.books.chapters.base', compact('book', 'edition', 'chapter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChapterRequest $request, $book_id, $edition_number, $chapter_id)
    {
        $this->bookChaptersService->updateChapter($request, $book_id, $edition_number, $chapter_id);
        session()->flash('success', __('global.chapters.success_update'));
        return back();
    }
    public function updateContent(Request $request, $book_id, $edition_number, $chapter_id)
    {
        $edition = $this->bookChaptersService->getEdition($book_id, $edition_number);
        $chapter = $edition->chapters()->where(['id' => $chapter_id])->firstOrFail();
        if($chapter->authors[0]->id != auth()->id()){
            abort(403);
        }
        $chapter->content = $request->get('content');
        $chapter->save();
        return ['status' => 'success'];
    }

    public function chapter_compeleted_email(Book $book, BookEdition $edition, BookChapter $chapter)
    {
        $book = $this->bookChaptersService->getBook($book->id);
        $edition = $this->bookChaptersService->getEdition($book->id, $edition->edition_number);

        //Send Email to Lead Author To inform That Chapter Finished
        $user = Auth::user(); // Co author
        $lead_author_id = $book->book_participants()->leadAuthor($book->id)->firstOrFail()->user_id;
        $lead_author = User::find($lead_author_id);

        $chapter_link = route('web.dashboard.books.editions.chapters.index', ['book' => $book->id , 'edition' => 1 , 'chapter' => $chapter->id]);
        $url_type = "chapter_completed";
        $chapter_name = '<strong style="color: #ce7852;" >'. $chapter->name .'</strong>';
        $book_name = '<strong style="color: #ce7852; ">'. $book->title .'</strong>';

        if ($user->name == $lead_author->name)
        {
            $user->notify(new RealTimeNotification( $lead_author->name . ' has finished chapter ' . $chapter_name . ' on ' . $book_name , $chapter_link , $url_type));
        }else{
            $lead_author->notify(new RealTimeNotification( $user->name . ' has finished chapter ' . $chapter_name . ' on ' . $book_name , $chapter_link , $url_type));
        }

        Mail::to($lead_author->email)->send(new InformLeadAuthorChapterCompleted($book, $edition, $chapter, $user,  $lead_author));
//        Mail::to($user->email)->send(new InformLeadAuthorChapterCompleted($book, $edition, $chapter, $user,  $lead_author));

        //End Email

        $chapter->update([
            'completed' => 1,
            'completed_at' => Now(),
        ]);

        session()->flash('success',  __('global.chapters.success_complete'));

        //this for downloading chapter
        event(new GenerateBookPDFS($book , 'chapter' , $chapter ));

        return back();
    }


    public function chapter_not_compeleted_email(Request $request,Book $book, BookEdition $edition, BookChapter $chapter)
    {
        $request->validate([
            'remark' => 'sometimes|required',
        ]);

        $book = $this->bookChaptersService->getBook($book->id);
        $edition = $this->bookChaptersService->getEdition($book->id, $edition->edition_number);

        //Send Email to Co Author To inform That Chapter Not Finished
        // this action is doing by the lead author , so needed to get the co author of this book  with edition and  chapter id
        $user = Auth::user(); // Lead uthor
        $co_author_id = $book->book_chapter_authors()->where('book_chapter_id', $chapter->id)->firstOrFail()->id;
        $co_author = User::find($co_author_id);

        $chapter_link = route('web.dashboard.books.editions.chapters.index', ['book' => $book->id , 'edition' => 1 , 'chapter' => $chapter->id]);
        $url_type = "chapter_not_completed";
        $chapter_name = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $chapter_link .'"> <strong style="color: #ce7852;" >'. $chapter->name .'</strong> </a>';
        $book_name = '<strong style="color: #ce7852; ">'. $book->title .'</strong>';

        $message ='Please be inform that Mr.'. $user->name . ' changed the status of
                                chapter ' . $chapter_name . ' on book ' . $book_name . ' from completed to be re-edited .' ;

        if($co_author_id != $user->id) {

            $co_author->notify(new RealTimeNotification( $message , $chapter_link , $url_type));

            Mail::to($co_author->email)->send(new InformCoAuthorChapterNotCompleted($book, $edition, $chapter, $user,  $co_author ,$request->remark));

        }else{

            $user->notify(new RealTimeNotification( $message, $chapter_link , $url_type));

        }

        //End Email
        $chapter->update([
            'completed' => 0,
            'completed_at' => Null,
        ]);

        session()->flash('success', __('global.chapters.reedit_chapter'));

        event(new DeleteBookFiles($book , 'chapter' , $chapter ));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book, BookEdition $edition, BookChapter $chapter)
    {
        $book = $this->bookChaptersService->getBook($book->id);

        $user = $book->lead_author;

        $email = $user ? $user->email : '';

        Mail::to($email)->send(new DeleteChapterApprove($book, $user, $chapter, $edition));

        return back()->with('success',  __('global.email_sent_successfully') . ' , ' . __('global.chapters.email_to_delete'));
    }

    public function delete_chapter(Book $book, BookEdition $edition, BookChapter $chapter)
    {
        $book = $this->bookChaptersService->getBook($book->id);

        $delete = $chapter->delete();

        if ($delete) {
            session()->flash('success',  __('global.chapters.success_delete'));
        } else {
            session()->flash('error',  __('global.chapters.error_delete'));
        }

        return redirect(route('web.dashboard.books.editions.chapters.index', ['book' => $book->id, 'edition' => 1]));
    }
}
