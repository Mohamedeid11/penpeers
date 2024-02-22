<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Dashboard\Authors\InviteAuthorRequest;
use App\Mail\DeleteAuthorApprove;
use App\Mail\InvitationEmail;
use App\Models\Book;
use App\Models\BookBuyRequest;
use App\Models\BookChapter;
use App\Models\BookParticipant;
use App\Models\BookRole;
use App\Models\EmailInvitation;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\AuthorBooksService;
use App\Services\AuthorsService;
use App\Services\BookAuthorsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardAuthorsController extends BaseWebController
{
    protected $bookAuthorsService;
    protected $authorsService;
    protected $bookRepository;
    protected $authorBooksService;
    protected $interestService;
    protected $userRepository;

    public function __construct(
        BookAuthorsService $bookAuthorsService,
        AuthorsService $authorsService,
        BookRepositoryInterface $bookRepository,
        AuthorBooksService $authorBooksService,
        InterestRepositoryInterface $interestService,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
        $this->bookAuthorsService = $bookAuthorsService;
        $this->authorsService = $authorsService;
        $this->bookRepository = $bookRepository;
        $this->authorBooksService = $authorBooksService;
        $this->interestService = $interestService;
        $this->userRepository = $userRepository;

        $this->middleware('checkIfUserAuthorized')->only(['acceptParticipation', 'deleteParticipation']);
        $this->middleware('check_plan_validity', ['only' => ['EmailReminder', 'acceptBookRequest', 'acceptParticipation']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $book_id)
    {
        $book = $this->bookAuthorsService->getBook($book_id);
        $book_roles = $this->bookAuthorsService->getBookRoles()->where('name', '!=', 'lead_author')->all();
        $type = $request->has('pending') ? 'pending' : 'active';
        $interests = $this->interestService->all();
        return view('web.dashboard.books.authors.authors', compact('book', 'book_roles', 'type','interests'));
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
    public function store(InviteAuthorRequest $request, $book_id)
    {
        $name = $request->name;
        $email = $request->email;



        $book = $this->bookAuthorsService->getBook($book_id);

        $book_role = BookRole::where('name', 'co_author')->first();

        if ($request->register_type == 1) {

            //registred user
            $emails = json_decode($email[0]);

            foreach($emails as $author_email) {

                $user = User::where('email', $author_email)->first();
                try {
                    $result =  $this->bookAuthorsService->inviteAuthorToBook($user, $book, $book_role);
                } catch (Exception $e) {
                    return back()->with('error',  __('global.invitation.already_sent'))->withInput();
                }
                $url = route('web.dashboard.author_receivedInvitations') ;
                $url_type = "received_invitations";

                $book_url = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $url .'">'. $book->title .'</a>';


                $user->notify(new RealTimeNotification( $book->lead_author->name . ' invited you to participate in this book ' . $book_url , $url ,$url_type));

                Mail::to($author_email)->send(new InvitationEmail(Auth::user()->name, $book, 'byUserName', $author_email));

            }

            return redirect(route('web.dashboard.author_participants'))->with('success', __('global.invitation.sent_successfully'));

        } else {
            $check_user = User::where('email' ,$email )->first();
            if ($check_user){
                return back()->with('error', __('global.author_already_registered', ['username'=> $check_user->username]));
            }
            // Non registred user
            $result = $this->bookAuthorsService->inviteAuthorToBookByEmail($name, $email, $book, $book_role);
            Mail::to($email)->send(new InvitationEmail(Auth::user()->name, $book, 'byEmail', $email));

            return redirect(route('web.dashboard.author_emailInvitations'))->with('success', __('global.invitation.sent_successfully'));

        }

    }

    public function EmailReminder(Request $request)
    {
        $book = $this->bookAuthorsService->getBook($request->book);
        $email = $request->email;
        $type = $request->type;
        Mail::to($email)->send(new InvitationEmail(Auth::user()->name, $book, $type, $email));
        return back()->with('success', __('global.invitation.sent_again_successfully'));
    }

    public function deleteInvitation(Request $request)
    {

        $book = $this->bookAuthorsService->getBook($request->book);
        $id = $request->id;
        $type = $request->type;

        try{
            if($type == 'book_participants'){
                $record = BookParticipant::where(['book_id'=>$book->id, 'status' => 0 , 'id' => $id ])->firstOrFail();
            }else{
                $record = EmailInvitation::where(['book_id'=>$book->id, 'status' => 0 , 'id' => $id ])->firstOrFail();
            }
            $record->delete();

        }catch(Exception $e){
            return back()->with('error', __('global.invitation.error_deleting'));
        }

        return back()->with('success', __('global.invitation.success_deleting'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book, BookParticipant $author)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $user = $book->lead_author;

        $email = $user->email;

        Mail::to($email)->send(new DeleteAuthorApprove($book, $user, $author));

        return back()->with('success', __('global.email_sent_successfully') .','. __('global.use_email_to_delete_author'));
    }

    public function delete_author(Book $book, $author_id)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $participant = BookParticipant::where(['user_id' => $author_id , 'book_id' => $book->id])->first();

        if ($participant->book_role->name == 'co_author')
        {

            $participant->delete();

            session()->flash('success',  __('global.success_delete_author'));

        }

        return redirect(route('web.dashboard.books.authors.index', ['book' => $book->id]));
    }

    public function getEmailInvitations()
    {
        auth()->user()->unreadNotifications()->where(function($query){
            $query->orWhere('data', 'LIKE', '%"url_type":"accepted_unregistered_author"%')
                  ->orWhere('data', 'LIKE', '%"url_type":"reject_unregistered_author"%'); })->delete();

        $emailInvitations = $this->authorsService->getEmailInvitations();
        return view('web.dashboard.books.invitations', compact('emailInvitations'));
    }
    public function getParticipants()
    {
        auth()->user()->unreadNotifications()->where(function($query){
                                                                $query->orWhere('data', 'LIKE', '%"url_type":"accept_registered_author"%')
                                                                      ->orWhere('data', 'LIKE', '%"url_type":"reject_registered_author"%'); })->delete();

        $participants = $this->authorsService->getParticipants();
        return view('web.dashboard.books.invitations', compact('participants'));
    }
    public function getReceivedInvitations()
    {
        auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"received_invitations"%')->delete();
        $receivedInvitations = $this->authorsService->getReceivedInvitations();
        return view('web.dashboard.books.invitations', compact('receivedInvitations'));
    }
    public function participationAcceptance(Request $request)
    {
        $book_id = $this->authorsService->acceptParticipation($request);
        return redirect(route('web.dashboard.books.editions.special_chapters.index', ['book'=>$book_id, 'edition'=> 1]))->with('success', __('global.invitation.success_accept'));
    }

    public function deletingParticipation(Request $request)
    {
        $this->authorsService->deleteParticipation($request);
        return back()->with('success', __('global.participation.delete_participant'));
    }
    public function rejectParticipation(Request $request)
    {
        $this->authorsService->rejectParticipation($request);
        if (!$request->type) {
            session()->flash('success',  __('global.invitation.success_reject'));
            return back();
        }else{
            return redirect(route('web.get_landing'))->with('success', __('global.invitation.success_reject'));

        }
    }
    public function getBookRequests($book_id)
    {
        auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"join_request"%')->delete();
        $book = $this->bookAuthorsService->getBook($book_id);
        return view('web.dashboard.books.authors.requests', compact('book'));
    }

    public function getBookBuyingRequests()
    {
        auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"buy_request"%')->delete();
        $user_books_ids =auth()->user()->lead_books->pluck('id');
        $buying_requests = BookBuyRequest::whereIn('book_id' , $user_books_ids )->get();

        return view('web.dashboard.books.authors.buying_requests', compact('buying_requests'));
    }

    public function acceptBookRequest($book_id, $request_id)
    {
        $book = $this->bookAuthorsService->getBook($book_id);
        $book_request = $book->requests()->where(['id' => $request_id])->first();
        if (!$book->participants()->where(['user_id' => $book_request->user->id])->first()) {
            $book->participants()->attach($book_request->user->id, ['status' => 1, 'book_role_id' => BookRole::where(['name' => 'co_author'])->first()->id]);
        }
        session()->flash('success', __('global.invitation.request_accepted'));
        $book_request->delete();
        return back();
    }

    public function checkChapterAuthorRole(Request $request, Book $book, BookChapter $chapter)
    {

        $book = $this->authorBooksService->getBook($book->id);
        $auth_author_role = BookParticipant::where(['book_id'=> $book->id, 'user_id'=> auth()->id()])->first()->book_role->name;
        $chapter = BookChapter::where(['book_id'=> $book->id, 'id'=> $chapter->id])->firstOrFail();

        if($chapter->authors[0]->id == auth()->id() && $auth_author_role == "lead_author"){

            return true;

        }

        return false;
    }
}
