<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Consults\CreateConsult;
use App\Http\Requests\StoreBookBuyRequestRequest;
use App\Http\Requests\Web\BookRequestRequest;
use App\Http\Requests\Web\Contacts\ContactUsRequest;
use App\Http\Requests\Web\Share\ShareLinkToMailRequest;
use App\Http\Requests\Web\Subscriptions\SubscribeRequest;
use App\Http\Resources\ViewBookResource;
use App\Mail\BookParticipationRequestAccepted;
use App\Mail\ShareLinkToEmail;
use App\Models\Blog;
use App\Models\Book;
use App\Models\BookBuyRequest;
use App\Models\BookChapter;
use App\Models\BookEdition;
use App\Models\BookParticipant;
use App\Models\BookParticipationRequest;
use App\Models\BookPublishRequest;
use App\Models\BookSpecialChapter;
use App\Models\Faq;
use App\Models\Guide;
use App\Models\Occupation;
use App\Models\PostCommentAndReply;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\BookParticipantRepositoryInterface;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\AuthorBooksService;
use App\Repositories\Eloquent\BaseRepository;
use App\Services\GeneralWebService;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Guid\Guid;
use PDF;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;


class LandingController extends BaseWebController
{
    protected $generalWebService;
    protected $authorBooksService;
    protected $BaseRepository;
    protected $bookParticipantRepository;
    protected $interestService;

    public function __construct(
        GeneralWebService $generalWebService,
        AuthorBooksService $authorBooksService,
        BaseRepository $BaseRepository,
        BookParticipantRepositoryInterface $bookParticipantRepository,
        InterestRepositoryInterface $interestService
    ) {
        parent::__construct();
        $this->generalWebService = $generalWebService;
        $this->authorBooksService = $authorBooksService;
        $this->BaseRepository = $BaseRepository;
        $this->bookParticipantRepository = $bookParticipantRepository;
        $this->interestService = $interestService;
    }

    public function index()
    {
        $sample_books = $this->generalWebService->getAllSampleBooks();
        $real_books = $this->generalWebService->getAllRealBooks();
        $receivable_books = $this->generalWebService->getReceivableBooks();
        $personalPlans = $this->generalWebService->getPersonalPlans();
        $corporatePlans = $this->generalWebService->getCorporatePlans();

        $authors = User::public()->inRandomOrder()->limit(8)->get();
        // $authorsThisMonth = $authors->whereMonth('created_at', Carbon::now()->month)->inRandomOrder()->limit(8)->get();
        // $authorsThisYear = $authors->whereYear('created_at', date('Y'))->inRandomOrder()->limit(8)->get();
        $blogs = $this->generalWebService->getBlogs();
        return view('web.index', compact('sample_books', 'real_books','authors', 'receivable_books', 'personalPlans', 'corporatePlans', 'blogs'));
    }
    public function subscribe(SubscribeRequest $request)
    {
        $this->generalWebService->subscribe($request->all());
        return redirect(route('web.get_landing') . '#subscribe_form_div');
    }
    public function viewBook(Request $request ,$slug, $edition_number = NULL)
    {
        $book = $this->generalWebService->getBookBySlug($slug);

        if($book->completed && $book->status != 1){
            abort(404);
        }

        $last_edition = $book->editions()->where('status', 1)->where('is_hidden', 1)->orderByDesc('edition_number')->first();
        $current_edition = $last_edition;
        $related = $this->generalWebService->getRelatedBooks($book);
        $bookEditions = $this->generalWebService->getAllBookEditions($book);
        $reviews = $book->reviews->sortByDesc('id');
        $type = request()->type;

        $title = $book->trans('title');
        $page = __('global.book', ['title'=>$title]);
        $desc = 'The First Platform for Authors and co-Authors';
        $image = asset(public_path('\images\web\logo\logo2.png' ));
//      $image = $book->front_cover;
//      $image = Storage::disk('public')->exists($image) ? storage_asset($image) : storage_asset($book->front_cover);
        SEOMeta::setTitle($page);
        SEOMeta::setDescription($desc);

        OpenGraph::setTitle($page);
        OpenGraph::setDescription($desc);
        OpenGraph::setUrl($request->fullUrl());
        OpenGraph::addImage($image, ['width'=>800, 'height'=>450]);

        TwitterCard::setTitle($page);
        TwitterCard::setUrl($request->fullUrl());
        TwitterCard::setDescription($desc);
        TwitterCard::addValue('image', $image);
        TwitterCard::setType("summary_large_image");
        TwitterCard::addImage($image);

        JsonLd::setTitle($page);
        JsonLd::setDescription($desc);
        JsonLd::addImage($image);
        return view('web.book', compact('book', 'related','reviews', 'current_edition', 'bookEditions', 'type' , 'image'));
    }

    public function shareLinkToMail(ShareLinkToMailRequest $request)
    {
        $email = $request->receiver_email;
        $email_type = str_contains($request->link, "blog-post")? "post" : "book";
        Mail::to($email)->send(new ShareLinkToEmail($request, $email_type));
        return back()->with('success', __('global.email_sent_successfully') . ' , ' . __('global.share_link_via_email'));

    }
    public function viewBookRequestForm($slug)
    {

        $book = $this->generalWebService->getBookBySlug($slug);
        $last_edition = $book->editions()->orderByDesc('edition_number')->first();
        $current_edition = $last_edition;
        $related = $this->generalWebService->getRelatedBooks($book);
        return view('web.book_request', compact('book', 'current_edition', 'related'));
    }
    public function addBookRequest(BookRequestRequest $request, $slug)
    {
        if((bool) User::where('email', $request->email)->first()){

            return back()->with('error',__('global.registered_email'));

        }

        if(auth()->check() && ! auth()->user()->is_public){

            return back()->with('error', __('global.error_subscription') );

        }

        $book = Book::where('slug', $slug)->whereHas('book_participants', function($query){

            $query->whereHas('book_role', function($Q){

                $Q->where('name', 'lead_author');

            })->whereHas('user', function($q){

                $q->public();

            });

        })->receivable()->where(function($qwry){

            $qwry->where('completed', 0)->orWhere('completed', 2);

        })->firstOrFail();

        if($book->sample){

            return back()->with('error', __('global.this_is_sample_book') );

        }

        $this->generalWebService->addBookRequest($request->except('g-recaptcha-response'), $book);

        return back();
    }
    public function getBookRequest($slug)
    {
        $book = Book::where('slug', $slug)->whereHas('book_participants', function($query){

            $query->whereHas('book_role', function($Q){

                $Q->where('name', 'lead_author');

            })->whereHas('user', function($q){

                $q->public();

            });

        })->receivable()->where(function($qwry){

            $qwry->where('completed', 0)->orWhere('completed', 2);

        })->firstOrFail();

        return view('web.requests.form', compact(['slug']));
    }

    public function buyBookRequest($slug)
    {
        $book = Book::where('slug', $slug)->where(function($qwry){

            $qwry->where('completed', 1);

        })->firstOrFail();

        if($book->price == null)
        {
            return back()->with('error', __('global.error_price_buying_request') );

        }else{

            return view('web.requests.buy_request_form', compact('book'));
        }
    }

    public function sendBuyRequest(StoreBookBuyRequestRequest $request , $id)
    {
//        if((bool) User::where('email', $request->email)->first()){
//
//            return back()->with('error',__('global.registered_email'));
//
//        }
//
//        if(auth()->check() && ! auth()->user()->is_public){
//
//            return back()->with('error', __('global.error_subscription') );
//
//        }

        $book = Book::findOrFail($id);

        if($book->sample){

            return back()->with('error', __('global.this_is_sample_book') );

        }

        $this->generalWebService->buyBookRequest($request->except('g-recaptcha-response'), $book);

        return back();
    }

    public function acceptBookRequest(Book $book, BookParticipationRequest $request)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $updated = $request->update(['accepted_at' => now()]);

        if ($updated) {

            if ($request->user_id) {
                $data = [
                    'book_id' => $request->book_id,
                    'user_id' => $request->user_id,
                    'status' => 1,
                    'book_role_id' => 2
                ];
                $this->bookParticipantRepository->create($data);
            }
            //Send Email inform user his request accepted
            $email = $request->email ? $request->email : $request->user->email;
            Mail::to($email)->send(new  BookParticipationRequestAccepted($request));

            session()->flash('success', __('global.request_accepted') );
        }

        return redirect(route('web.dashboard.books.requests', ['book' => $book]));
    }

    public function deleteBookRequest(Book $book, BookParticipationRequest $request)
    {
        $book = $this->authorBooksService->getBook($book->id);

        $deleted = $request->delete();

        if ($deleted) {
            session()->flash('success',   __('global.request_deleting') );
        }

        return redirect(route('web.dashboard.books.requests', ['book' => $book]));
    }
    public function page($page)
    {
        $page = $this->generalWebService->getPage($page);
        $name = __('global.global_pages.' . $page->name);
        if (!$page) {
            abort(404);
        }
        return view('web.page', compact('page', 'name'));
    }
    public function about()
    {
        $page = $this->generalWebService->getPage('about');
        $name = __('global.global_pages.' . $page->name);
        return view('web.page', compact('page', 'name'));
    }
    public function overview()
    {
        $page = $this->generalWebService->getPage('overview');
        $name = __('global.global_pages.' . $page->name);
        return view('web.page', compact('page', 'name'));
    }

    public function terms()
    {
        $page = $this->generalWebService->getPage('terms');
        $name = __('global.global_pages.' . $page->name);
        return view('web.page', compact('page', 'name'));
    }
    public function faq()
    {
        $faqs = Faq::all();
        return view('web.faq', compact('faqs'));
    }
    public function howItWorks()
    {
        $guides = Guide::all();
        return view('web.how-it-works', compact('guides'));
    }
    public function contact()
    {
        $contact_info = Setting::whereIn('name', ['contact_emails', 'contact_numbers', 'office_location'])->get(['name', 'value']);
        return view('web.contact', compact('contact_info'));
    }
    public function underConstructiosPages()
    {
        return view('web.under-construction');
    }
    public function contactUs(ContactUsRequest $request)
    {
        $this->generalWebService->contactUs($request->all());
        return redirect()->back();
    }
    public function webAuthors(Request $request)
    {
        $authors = User::public();

        if (request()->get('search')) {
            $authors = $authors->where('name', 'like', '%' . request()->get('search') . '%')->orWhere('email', 'like', '%' . request()->get('search') . '%');
        }

        if (request()->get('interests')) {
            $interest_id = request()->get('interests');
            $authors = $authors->whereHas('interests', function (Builder $q) use($interest_id) {
                $q->where(['interest_id' => $interest_id ]);
            });
        }

        $authors = $authors->paginate(12)->appends($request->query());
        $interests = $this->interestService->all();
        return view('web.authors', compact('authors','interests'));
    }

    public function webAuthorBooks(Request $request, $author_id, $type = null)
    {
        $author = User::with('books', 'interests', 'country')->where('id', $author_id)->firstOrFail();

        if($type == 'lead_books')
        {
            $books = paginateArray($author->lead_books->filter(function($book){
                return  $book->where('status', 1)->where('completed', 1) ||
                        $book->where('receive_requests', true)->where(function($Q){
                            $Q->where('completed', 0)->orWhere('completed', 1);
                        });
                })->all());

        } else if($type == 'co_books')
        {
            $books = paginateArray($author->co_books->filter(function($book){
                return  $book->where('status', 1)->where('completed', 1) ||
                        $book->where('receive_requests', true)->where(function($Q){
                            $Q->where('completed', 0)->orWhere('completed', 1);
                        });
                })->all());

        } else if($type == 'all_books')
        {
            $books = paginateArray($author->books->filter(function($book){
                return  $book->where('status', 1)->where('completed', 1) ||
                        $book->where('receive_requests', true)->where(function($Q){
                            $Q->where('completed', 0)->orWhere('completed', 1);
                        });
                })->all());

        }

        return view('web.author-books', compact('author', 'books', 'type'));
    }
    public function webBooks($genre = NULL)
    {
        $books = $this->generalWebService->getWebBooks($genre);
        return view('web.books', compact('books'));
    }


    public function webPopularBooks($genre = NULL)
    {
        $books = $this->generalWebService->getWebPopularBooks($genre);
        return view('web.popular-books', compact('books'));
    }

    public function webBooksNeedAuthors($genre = NULL)
    {
        $books = $this->generalWebService->getBooksNeedAuthors($genre);
        return view('web.books-need-authors', compact('books'));
    }

    public function consult()
    {
        $occupations = Occupation::all();
        return view('web.consult', compact('occupations'));
    }

    public function consultForm(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'occupation_id' => 'required|exists:occupations,id',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);
        $this->generalWebService->createConsult($request->all());
        return redirect()->back();
    }

    public function blogPosts()
    {
        $blog_posts = Blog::whereHas('user', function($q){

            $q->public();

        })->approved();

        $posts = $blog_posts->orderBy('id', 'desc')->paginate(5);

        $recent_posts = $blog_posts->orderBy('id', 'desc')->limit(5)->get();

        return view('web.blog-posts', compact('posts', 'recent_posts'));
    }

    public function blogPost(Request $request,$id)
    {
        $blog_posts = Blog::whereHas('user', function($q){

            $q->public();

        })->approved();

        $recent_posts = $blog_posts->orderBy('id', 'desc')->limit(5)->get();

        $post = $blog_posts->where('id', $id)->firstOrFail();

        $post_comments = $post->comments->sortByDesc('id');

        $title = $post->trans('title');
        $page = __('global.book', ['title'=>$title]);
        $desc = 'The First Platform for Authors and co-Authors';
        $image = asset(public_path('\images\web\logo\logo2.png' ));
//        $image = $post->image;
//        $image = Storage::disk('public')->exists($image) ? storage_asset($image) : storage_asset($post->image);
        SEOMeta::setTitle($page);
        SEOMeta::setDescription($desc);

        OpenGraph::setTitle($page);
        OpenGraph::setDescription($desc);
        OpenGraph::setUrl($request->fullUrl());
        OpenGraph::addImage($image, ['width'=>800, 'height'=>450]);

        TwitterCard::setTitle($page);
        TwitterCard::setUrl($request->fullUrl());
        TwitterCard::setDescription($desc);
        TwitterCard::addValue('image', $image);
        TwitterCard::setType("summary_large_image");
        TwitterCard::addImage($image);

        JsonLd::setTitle($page);
        JsonLd::setDescription($desc);
        JsonLd::addImage($image);
        return view('web.post', compact('post', 'recent_posts', 'post_comments'));
    }

    protected function getAllBookChapters(Book $book, BookEdition $edition)
    {

//        $dedicationsChapters = $book->book_special_chapters()->where('book_edition_id', $edition->id)->whereHas('special_chapter', function ($q) {
//            $q->dedications();
//        })->get();
//
//        $acknowledgmentsChapters = $book->book_special_chapters()->where('book_edition_id', $edition->id)->whereHas('special_chapter', function ($q) {
//            $q->acknowledgments();
//        })->get();

        $introChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function($q){
            $q->intro();
        })->get();


        $regularChapters = $book->book_chapters()->with('authors')->get();

        return [
            'book' => $book,
//            'edition' => $edition,
//            'dedicationsChapters' => $dedicationsChapters,
//            'acknowledgmentsChapters' => $acknowledgmentsChapters,
            'introChapters' => $introChapters,
            'regularChapters' => $regularChapters
        ];
    }

    public function previewBook(Book $book)
    {
//        return view('web.dashboard.books.read-book', compact('book'));

        return view('web.dashboard.books.read-book-paginate', compact('book'));

    }



    public function previewBookChapter(Book $book, BookEdition $edition)
    {
        $book->load('participants', 'book_special_chapters' , 'book_chapters');
        $book_chapters =$book->book_chapters()->with('authors')->paginate(1);
        return response()->json([
            'book_chapter' =>  $book_chapters,
            'authors' =>  $book_chapters->pluck('authors'),
            'links' => [
                'lastPage' => $book_chapters->lastPage(),
                'perPage' => $book_chapters->perPage(),
                'currentPage' => $book_chapters->currentPage(),
                'nextPageUrl' => $book_chapters->nextPageUrl(),
                'previousPageUrl' => $book_chapters->previousPageUrl(),
            ],
        ]);
    }

//    public function downloadBook(Book $book, BookEdition $edition)
//    {
//        $data = $this->getAllBookChapters($book, $edition);
//        view()->share('data',$data);
//        $pdf = PDF::loadView('web.partials.download-book-pdf', $data);
//
//        return $pdf->download($book->title . ".pdf");
////        return view('web.partials.download-book-pdf' ,compact('data'));
//    }

    public function addComment(Request $request , Blog $blog, PostCommentAndReply $comment)
    {
        try{

            PostCommentAndReply::create([
                'type' => $request->type ,
                'user_id' => Auth::id(),
                'comment' => $request->comment,
                'blog_id' => $blog->id,
                'comment_id' => $comment->id
            ]);

            $error_message = __('global.error_comment');
            $success_message = __('global.success_comment');
            $url = route('web.blog_post', ['id' => $blog->id]) ;

            //send notification to lead author
            if ($request->type  == "reply" && Auth::id() != $comment->user->id)
            {
                $url_type = 'replay_comment';
                $message = ' has replied to your Comment <strong style="color: #ce7852;">' . $comment->comment . '</strong> ' ;
                $comment->user->notify(new RealTimeNotification( '<strong style="color: #ce7852;">'. (auth()->user()->name)  .'</strong>'. $message  , $url , $url_type));;
                $error_message = __('global.error_replay_comment');
                $success_message = __('global.success_replay_comment');
            }elseif (Auth::id() != $blog->user->id){
                $url_type = 'comment_feed';
                $message = ' has commented to your Feed <strong style="color: #ce7852;">'. $blog->title .'</strong> ';
                $blog->user->notify(new RealTimeNotification( '<strong style="color: #ce7852;">'. (auth()->user()->name)  .'</strong>'. $message  , $url , $url_type));;
            }


        }catch(Exception $e){
            return back()->With('error', $error_message );
        }

        return back()->With('success', $success_message );

    }

    public function readNotification(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->notification_id)->delete();

        return redirect($request->url);

    }

    public function downloadChapter(Book $book)
    {
        if ($book->completed == 0)
        {
            return response()->json([
                'status' =>  false,
                'message' =>  "You need to complete this book first",
            ]);
        }

        if ($book->sample == 1)
        {
            return response()->json([
                'status' =>  false,
                'message' =>  "You can not download sample book",
            ]);

        }

        $pdf = PDFMerger::init();

        $main_path = public_path('storage/uploads/book_pdfs/')  . $book->title ;

        $pdf->addPDF($main_path . '/0.pdf', 'all');
        $pdf->addPDF($main_path . '/1.pdf', 'all');

        $chapters = $book->book_chapters();

        foreach ($chapters->get() as $chapter)
        {

            $pdf->addPDF($main_path . '/' . ($chapter->order + 1) . '.pdf', 'all');

        }

        $last_chapter_order = $chapters->orderBy('order', 'desc')->first();

        $pdf->addPDF($main_path . '/'. ($last_chapter_order->order + 2) . '.pdf', 'all');

        $pdf->merge();
        $pdf->setFileName($book->title . '.pdf');
        return $pdf->download();
    }
}
