<?php

namespace App\Services;

use App\Mail\EmailLeadAuthorForBuyingBook;
use App\Mail\EmailLeadAuthorForJoiningBook;
use App\Mail\EmailRequesterTheRequestSent;
use App\Mail\ThanksForSubscription;
use App\Models\Blog;
use App\Models\Book;
use App\Models\BookBuyRequest;
use App\Models\BookEdition;
use App\Models\BookParticipant;
use App\Models\BookParticipationRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\ConsultRepositoryInterface;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class GeneralWebService
{
    protected $userRepository;
    protected $genresRepository;
    protected $specialChaptersRepository;
    protected $bookRepository;
    protected $bookRoleRepository;
    protected $pageRepository;
    protected $subscriptionRepository;
    protected $contactMessageRepository;
    protected $consultRepository;
    private $planRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        GenreRepositoryInterface $genresRepository,
        SpecialChapterRepositoryInterface $specialChaptersRepository,
        BookRepositoryInterface $bookRepository,
        BookRoleRepositoryInterface $bookRoleRepository,
        PageRepositoryInterface $pageRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
        ContactMessageRepositoryInterface $contactMessageRepository,
        ConsultRepositoryInterface $consultRepository,
        PlanRepositoryInterface $planRepository
    ) {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
        $this->specialChaptersRepository = $specialChaptersRepository;
        $this->bookRepository = $bookRepository;
        $this->bookRoleRepository = $bookRoleRepository;
        $this->pageRepository = $pageRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->contactMessageRepository = $contactMessageRepository;
        $this->consultRepository = $consultRepository;
        $this->planRepository = $planRepository;
    }
    public function getPopularBooks()
    {
        return Book::where(['status' => 1, 'completed' => 1])->popular()->limit(8)->get();
    }
    public function getAllBooks()
    {
        return Book::where(['status' => 1, 'completed' => 1])->orderBy('created_at', 'desc')->limit(8)->get();
    }

    public function getAllSampleBooks()
    {
        return Book::where(['status' => 1, 'completed' => 1, 'sample' => true])->orderBy('created_at', 'desc')->limit(8)->get();
    }

    public function getAllRealBooks()
    {
        return Book::where(['status' => 1, 'completed' => 1, 'sample' => false])->orderBy('created_at', 'desc')->limit(8)->get();
    }

    public function getWebBooks($genre_id = NULL)
    {
        // dd(request()->filter_type);
        // $books = Book::where(['status' => 1, 'completed' => 1])->public()->published();
        $books = Book::where(['status' => 1, 'completed' => 1]);
        $books = request()->filter_type == 'for_sale' ? $books->where('sample', 0) : $books->where('sample', 1);
        return $genre_id ? $books->where('genre_id', $genre_id)->paginate(12) : $books->paginate(12);
    }
    public function getAllBookEditions(Book $book)
    {
        return $book->editions()->published()->where('is_hidden', 1)->get();
    }
    public function getBookBySlug($slug)
    {
        return $this->bookRepository->getBy('slug', $slug);
    }
    public function getAbout()
    {
        return $this->pageRepository->getBy('name', 'about');
    }

    public function getPage($page)
    {
        return $this->pageRepository->getBy('name', $page);
    }
    public function getBlogs()
    {
        return Blog::whereHas('user', function($q){

            $q->public();

        })->where('approved', 1)->orderBy('id', 'DESC')->limit(4)->get();
    }
    public function subscribe(array $data)
    {
        unset($data['g-recaptcha-response']);
        $this->subscriptionRepository->create($data);
        Mail::to($data['email'])->send(new ThanksForSubscription($data['name']));
        session()->flash('success', __('admin.subscribed_successfully'));
    }
    public function contactUs(array $data)
    {
        unset($data['g-recaptcha-response']);
        $this->contactMessageRepository->create($data);
        session()->flash('success', __('global.message_sent'));
    }
    public function createConsult(array $data)
    {
        unset($data['g-recaptcha-response']);
        $this->consultRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing' => __('global.consult')]));
    }

    public function getRelatedBooks($book)
    {

        // return
        //     $book ?
        //     $this->bookRepository->all(true)->orderByRaw("FIELD(genre_id, $book->genre_id)")->limit(8)->get() :
        //     [];

        return Book::where('id', '!=', $book->id)->where('genre_id', $book->genre_id)->where(function($qury){

            $qury->where(['status' => 1, 'completed' => 1])->orWhere(function($Qry){

                $Qry->whereHas('book_participants', function($query){

                    $query->whereHas('book_role', function($Q){

                        $Q->where('name', 'lead_author');

                    })->whereHas('user', function($q){

                        $q->public();

                    });

                })->receivable()->where(function($qwry){

                    $qwry->where('completed', 0)->orWhere('completed', 2);

                });
            });

        })->limit(8)->get();
    }

    public function getReceivableBooks()
    {
        return Book::whereHas('book_participants', function($query){

            $query->whereHas('book_role', function($Q){

                $Q->where('name', 'lead_author');

            })->whereHas('user', function($q){

                $q->public();

            });

        })->receivable()->where(function($qwry){

            $qwry->where('completed', 0)->orWhere('completed', 2);

        })->orderBy('created_at', 'desc')->limit(8)->get();
    }

    public function getBooksNeedAuthors($genre_id = NULL)
    {
        if ($genre_id) {

            $books = Book::whereHas('book_participants', function($query){

                $query->whereHas('book_role', function($Q){

                    $Q->where('name', 'lead_author');

                })->whereHas('user', function($q){

                    $q->public();

                });

            })->receivable()->where('genre_id', $genre_id)->where(function($qwry){

                $qwry->where('completed', 0)->orWhere('completed', 2);

            })->paginate(12);

        } else {

            $books = Book::whereHas('book_participants', function($query){

                $query->whereHas('book_role', function($Q){

                    $Q->where('name', 'lead_author');

                })->whereHas('user', function($q){

                    $q->public();

                });

            })->receivable()->where(function($qwry){

                $qwry->where('completed', 0)->orWhere('completed', 2);

            })->paginate(12);

        }

        return $books;
    }

    public function addBookRequest(array $data, $book)
    {
        $data['user_id'] = auth()->check() ? auth()->guard('web')->user()->id : null;

        $book_request = $book->requests()->save(new BookParticipationRequest($data));
        session()->flash('success',  __('global.success_send_request') );
        // Send Email to the owner of Action
        $sender_email = auth()->check() ? auth()->guard('web')->user()->email: $data['email'];
//        Mail::to($sender_email)->send(new EmailRequesterTheRequestSent($book_request));

        // send mail to leader author
        $lead_author = $book_request->book->lead_author;
        Mail::to($lead_author->email)->send(new EmailLeadAuthorForJoiningBook($book_request));

        $url = route('web.dashboard.books.requests' , ['book' => $book_request->book->id]);
        $url_type =  'join_request';

        $book_url = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $url .'">'. $book_request->book->title .'</a>';
        $lead_author->notify(new RealTimeNotification( 'you got New book join request to book ' . $book_url , $url , $url_type));

        return true;
    }

    public function buyBookRequest(array $data , $book)
    {
//        $data['user_id'] = auth()->check() ? auth()->guard('web')->user()->id : null;

        $buy_request = $book->requests()->save(new BookBuyRequest($data));
        session()->flash('success',  __('global.success_buy_request') );


        // send mail to leader author
        $lead_author = $buy_request->book->lead_author;

        $url = route('web.dashboard.buying_requests' );
        $url_type =  'buy_request';

        $book_url = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $url .'">'. $buy_request->book->title  .'</a>';
        $lead_author->notify(new RealTimeNotification( ('You have a new buying request for ' . $book_url . ' book') , $url , $url_type));


        Mail::to($lead_author->email)->send(new EmailLeadAuthorForBuyingBook($buy_request));

        return true;
    }

    public function getWebPopularBooks($genre_id = NULL)
    {
        $books = Book::where(['status' => 1, 'completed' => 1])->popular();
        return $genre_id ? $books->where('genre_id', $genre_id)->paginate(12) : $books->paginate(12);
    }

    public function getPersonalPlans()
    {
        return $this->planRepository->all(true)->personal()->get();
    }

    public function getCorporatePlans()
    {
        return $this->planRepository->all(true)->corporate()->get();
    }

    public function CKEditorDownload($html, $book , $type , $chapter = null )
    {
        return $this->bookRepository->CKEditorDownload($html, $book , $type , $chapter);
    }
}
