<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBooksController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\BookEditionsController;
use App\Http\Controllers\Admin\ConsultsController;
use App\Http\Controllers\Admin\ContactMessagesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\GenresController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\GuidesController;
use App\Http\Controllers\Admin\NewsletterEmailsController;
use App\Http\Controllers\Admin\OccupationsController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\PublishRequestsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinksController;
use App\Http\Controllers\Admin\SubscriptionsController;
use App\Http\Controllers\Web\Dashboard\AccountController;
use App\Http\Controllers\Web\Dashboard\DashboardAuthorsController;
use App\Http\Controllers\Web\Dashboard\DashboardBlogsController;
use App\Http\Controllers\Web\Dashboard\DashboardChaptersController;
use App\Http\Controllers\Web\Dashboard\DashboardSpecialChaptersController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\UserAuthController;
use App\Http\Controllers\Web\UserLibraryController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Web\Dashboard\DashboardEditionsController;
use App\Http\Controllers\Web\Dashboard\UserDashboardController;
use App\Http\Controllers\Web\Dashboard\DashboardBooksController;
use App\Http\Controllers\Web\Dashboard\EmployeeDashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/mailable', function () {
//     $book = App\Models\Book::find(1);

//     return new App\Mail\InvitationEmail("islam", $book, 'byEmail');
// });

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {
    Route::get('/read-notification' , [LandingController::class , 'readNotification'])->name('notification.read');
    Route::get('/download-chapter/{book}' , [LandingController::class , 'downloadChapter'])->name('download.chapter');

    Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'sign_not_logged_in'])->name('verification.verify');
    Route::get('account/{username}/pay', [AccountController::class, 'getPaymentData'])->name('account.pay');

    Route::get('/', [LandingController::class, 'index'])->name('web.get_landing');
    Route::get('/book/request/{slug}', [LandingController::class, 'viewBookRequestForm'])->name('web.book_request');

    Route::get('/book/{slug}/{edition_number?}', [LandingController::class, 'viewBook'])->name('web.view_book');
    Route::post('/share-link-to-mail', [LandingController::class, 'shareLinkToMail'])->name('web.shareLinkToMail');
    Route::get('about', [LandingController::class, 'about'])->name('web.get_about');
    Route::get('overview', [LandingController::class, 'overview'])->name('web.get_overview');
    Route::get('terms', [LandingController::class, 'terms'])->name('web.get_terms');
    Route::get('faq', [LandingController::class, 'faq'])->name('web.get_faq');
    Route::get('contact', [LandingController::class, 'contact'])->name('web.get_contact');
    Route::get('under-construction', [LandingController::class, 'underConstructiosPages'])->name('web.under_construction');
    Route::post('contactUs', [LandingController::class, 'contactUs'])->name('web.contactUs');
    Route::get('how_it_works', [LandingController::class, 'howItWorks'])->name('web.get_how_it_works');
    Route::get('page/{name}', [LandingController::class, 'page'])->name('web.page');
    Route::post('subscribe', [LandingController::class, 'subscribe'])->name('web_subscribe');
    Route::get('authors', [LandingController::class, 'webAuthors'])->name('web.authors');
    Route::get('author-books/{author}/books-type/{type?}', [LandingController::class, 'webAuthorBooks'])->name('web.author_books');
    Route::get('books/{genre?}', [LandingController::class, 'webBooks'])->name('web.books');
    Route::get('popular-books/{genre?}', [LandingController::class, 'webPopularBooks'])->name('web.popular_books');
    Route::get('books-need-authors/{genre?}', [LandingController::class, 'webBooksNeedAuthors'])->name('web.books_need_authors');
    Route::get('consult', [LandingController::class, 'consult'])->name('web.consult');
    Route::post('consult', [LandingController::class, 'consultForm'])->name('web.consult_form');
    Route::get('blog-posts', [LandingController::class, 'blogPosts'])->name('web.blog_posts');
    Route::get('blog-post/{id}', [LandingController::class, 'blogPost'])->name('web.blog_post');
//    Route::get('download-book/{book}/edition/{edition}', [LandingController::class, 'downloadBook'])->name('web.download_book');

    Route::post('/book/request/{slug}', [LandingController::class, 'addBookRequest'])->name('web.send_book_request');
    Route::get('/book/request/{slug}', [LandingController::class, 'getBookRequest'])->name('web.get_book_request');
    Route::get('/buyRequest/{slug}', [LandingController::class, 'buyBookRequest'])->name('web.buy_book_request');
    Route::post('/request/{id}', [LandingController::class, 'sendBuyRequest'])->name('web.send_buy_request');

    Route::post('/book/request/{book}/{request}/', [LandingController::class, 'acceptBookRequest'])->name('web.accept_book_request');
    Route::delete('/book/request/{book}/{request}/', [LandingController::class, 'deleteBookRequest'])->name('web.delete_book_request');
    Route::get('reject-participation', [DashboardAuthorsController::class, 'rejectParticipation'])->name('web.dashboard.author_rejectParticipation');

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('web.get_login');
        Route::post('login', [UserAuthController::class, 'login'])->name('web.post_login');
        Route::get('register/{company?}/{hash?}/', [UserAuthController::class, 'showRegisterForm'])->name('web.get_register');
        Route::post('register/{company?}/{hash?}/{email?}', [UserAuthController::class, 'register'])->name('web.post_register');
        Route::get('/forgot-password', [UserAuthController::class, 'showResetRequestForm'])->name('password.request');
        Route::post('/forgot-password', [UserAuthController::class, 'submitResetRequest'])->name('password.email');
        Route::get('/reset-password/{token}', [UserAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [UserAuthController::class, 'submitReset'])->name('password.update');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::post('logout', [UserAuthController::class, 'logout'])->name('web.post_logout');
        Route::get('/assure_login', [UserAuthController::class, 'assureLogin'])->name('web.get_assure_login');

        Route::group(['middleware' => 'not_verified'], function () {
            Route::get('/email/verify', [UserAuthController::class, 'verify'])->name('verification.notice');
            // Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'sign'])->middleware('signed')->name('verification.verify');
            Route::post('/email/verification-notification', [UserAuthController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
        });

        Route::group(['middleware' => 'verified'], function () {

            Route::post('/book/{slug}/review', [DashboardBooksController::class, 'bookReview'])->name('submit_book_review');

            Route::name('web.library.')->prefix('library')->group(function () {
                Route::get('/', [UserLibraryController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'limit_co_author_actions'], function () {

                Route::name('web.dashboard.')->prefix('dashboard')->group(function () {
                    Route::get('preview-book/{book}/edition/{edition}/', [LandingController::class, 'previewBook'])->name('preview_book');

                    Route::get('/', [UserDashboardController::class, 'index'])->name('index');

                    Route::get('/books/contributors', [DashboardBooksController::class, 'contributors'])->name('books.contributors');


                    Route::resource('books', DashboardBooksController::class);

                    Route::name('books.')->prefix('books')->group(function () {

//                        Route::get('{book}/downolad-pdf', [DashboardBooksController::class, 'download_book_pdf']);
                        Route::get('{book}/delete', [DashboardBooksController::class, 'delete_book'])->name('delete_book');
                        Route::get('{book}/cancel-delete', [DashboardBooksController::class, 'cancel_delete_book'])->name('cancel_delete_book');
                        Route::resource('{book}/editions', DashboardEditionsController::class)->middleware('book_completed_actions');;
                        Route::get('{book}/complete', [DashboardBooksController::class, 'completeBook'])->name('complete_book');
                        Route::get('{book}/toggle', [DashboardBooksController::class, 'toggleBookVisibiltyStatus'])->name('toggle_visibility_status');
                        Route::get('{book}/redit', [DashboardBooksController::class, 'reditBook'])->name('redit_book');

                        Route::name('editions.')->prefix('{book}/editions')->group(function () {

                            Route::get('{edition}/special_chapters/{special_chapter}/delete', [DashboardSpecialChaptersController::class, 'delete_special_chapter'])->name('special_chapters.delete_special_chapter');
                            Route::resource('{edition:edition_number}/special_chapters', DashboardSpecialChaptersController::class)->middleware('book_completed_actions');
                            Route::post('{edition}/special_chapters/{special_chapter}/send-email', [DashboardSpecialChaptersController::class, 'chapter_compeleted_email'])->name('special_chapters.completed_email');
                            Route::post('{edition}/special_chapters/{special_chapter}/send-email/not-completed', [DashboardSpecialChaptersController::class, 'chapter_not_compeleted_email'])
                                ->name('special_chapters.not_completed_email')->middleware('book_completed_actions');

                            Route::get('{edition}/chapters/{chapter}/delete', [DashboardChaptersController::class, 'delete_chapter'])->name('chapters.delete_chapter');
                            Route::resource('{edition}/chapters', DashboardChaptersController::class)->middleware('book_completed_actions');;
                            Route::post('{edition}/chapters/{chapter}/send-email', [DashboardChaptersController::class, 'chapter_compeleted_email'])->name('chapters.completed_email');
                            Route::post('{edition}/chapters/{chapter}/send-email/not-completed', [DashboardChaptersController::class, 'chapter_not_compeleted_email'])
                                ->name('chapters.not_completed_email')->middleware('book_completed_actions');

                            Route::put('{edition:edition_number}/toggle', [DashboardEditionsController::class, 'toggle'])->name('toggle')->middleware('book_completed_actions');
                            Route::put('{edition:edition_number}/publish', [DashboardEditionsController::class, 'publishEdition'])->name('publish')->middleware('book_completed_actions');
                            Route::put('{edition:edition_number}/reedit', [DashboardEditionsController::class, 'reeditEdition'])->name('reedit')->middleware('book_completed_actions');
                            Route::get('{edition}/delete', [DashboardEditionsController::class, 'delete_edition'])->name('delete_edition');
                            Route::get('{edition:edition_number}/settings', [DashboardEditionsController::class, 'editionSettings'])->name('edition_settings');
                            // Route::get('/{edition}', [DashboardEditionsController::class, 'update'])->name('update');
                        });

                        Route::get('{book}/authors/{author}/delete', [DashboardAuthorsController::class, 'delete_author'])->name('authors.delete_author');
                        Route::resource('{book}/authors', DashboardAuthorsController::class)->middleware('book_completed_actions');
                        Route::post('authors/email-reminder', [DashboardAuthorsController::class, 'EmailReminder'])->name('authors.email_reminder')->middleware('book_completed_actions');
                        Route::delete('authors/invitations/delete', [DashboardAuthorsController::class, 'deleteInvitation'])->name('authors.delete_invitation')->middleware('book_completed_actions');
                        Route::get('{book}/requests', [DashboardAuthorsController::class, 'getBookRequests'])->name('requests');
                        Route::post('{book}/request/{request}', [DashboardAuthorsController::class, 'acceptBookRequest'])->name('acceptBookRequest');

                    });

                    Route::get('/buying-requests', [DashboardAuthorsController::class, 'getBookBuyingRequests'])->name('buying_requests');



                    Route::get('email_invitations', [DashboardAuthorsController::class, 'getEmailInvitations'])->name('author_emailInvitations');
                    Route::get('participants', [DashboardAuthorsController::class, 'getParticipants'])->name('author_participants');
                    Route::get('received', [DashboardAuthorsController::class, 'getReceivedInvitations'])->name('author_receivedInvitations');
                    Route::post('accept-participation', [DashboardAuthorsController::class, 'participationAcceptance'])->name('author_acceptParticipation');
                    Route::post('delete-participation', [DashboardAuthorsController::class, 'deletingParticipation'])->name('author_deleteParticipation');

                    Route::resource('blogs', DashboardBlogsController::class);
                    Route::name('blogs.comments')->prefix('blogs/{blog}/comments/{comment?}')->group(function () {
                          Route::post('/',[LandingController::class, 'addComment']);
                    });

                    Route::name('account.')->prefix('account')->group(function () {
                        Route::put('/password', [AccountController::class, 'changePassword'])->name('password.change');
                        Route::get('/edit', [AccountController::class, 'index'])->name('edit.show');
                        Route::put('/edit', [AccountController::class, 'editDetails'])->name('edit.update');
                        Route::get('/status', [AccountController::class, 'accountStatus'])->name('status');
                        Route::put('/status', [AccountController::class, 'toggleStatus'])->name('toggle');
                        Route::get('/validity', [AccountController::class, 'checkAccountValidity'])->name('validity');
                        Route::get('/danger', [AccountController::class, 'accountDangerZone'])->name('danger');
                        Route::get('/danger_approve', [AccountController::class, 'MailToDeleteAccount'])->name('MailToDeleteAccount');
                        Route::get('{hashing_id?}/send_mail', [AccountController::class, 'deleteAccount'])->name('deleteAccount');
                        Route::post('/payment/prepare/credi', [AccountController::class, 'preparePaymentCredi'])->name('preparePayment.crediMax');
                    });

                    Route::get('profile', function () {
                        return view('web.dashboard.account.edit');
                    })->name('account');

                    Route::name('employees.')->prefix('employees')->group(function () {
                        // Route::get('/', [EmployeeDashboardController::class, 'employees'])->name('list');
                        // Route::post('/add', [EmployeeDashboardController::class, 'addEmployee'])->name('add');
                        // Route::post('/upload-excel', [EmployeeDashboardController::class, 'addEmployeeExcel'])->name('uploadExcel');
                        // Route::delete('account/employees/{employee}', [EmployeeDashboardController::class, 'deleteEmployee'])->name('delete');
                        // Route::post('account/employees/{employee}/resend', [EmployeeDashboardController::class, 'resendEmployeeInvitation'])->name('resendInvitation');
                    });


                    //                Route::get('books/{book_id}/manage', [DashboardBooksController::class, 'manage'])->name('books.manage');
                    //                Route::get('books/{book_id}/manage/{edition_num}/editor',
                    //                    [DashboardBooksController::class, 'manageEdition'])->name('books.manage_edition');
                    //                Route::post('books/{book_id}/editions',
                    //                    [DashboardBooksController::class, 'addEdition'])->name('books.editions.store');
                    //                Route::get('books/{book_id}/chapters/{edition_num}/editor',
                    //                    [DashboardBooksController::class, 'manageChapters'])->name('books.manage_chapters');
                    //                Route::post('books/{book_id}/manage/{edition_num}/special_chapter',
                    //                    [DashboardBooksController::class, 'addSpecialChapter'])->name('books.add_special_chapter');
                    //                Route::post('books/{book_id}/manage/{edition_num}/chapter',
                    //                    [DashboardBooksController::class, 'addChapter'])->name('books.add_chapter');

                    //                Route::get('books/{book_id}/authors', [DashboardBooksController::class, 'authors'])->name('books.authors');
                    //                Route::post('books/{book_id}/authors', [DashboardBooksController::class, 'inviteAuthor'])->name('books.authors.invite');
                });
            });
        });
    });


    Route::name('admin.')->prefix('admin')->group(function () {

        Route::group(['middleware' => 'guest'], function () {
            Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('fake_get_login');
            Route::post('login', [AdminAuthController::class, 'fake_login'])->name('fake_post_login');
        });


        Route::group(['middleware' => 'guest'], function () {
            Route::get('/sec-signing-form', [AdminAuthController::class, 'showLoginForm'])->name('get_login');
            Route::post('/sec-signing-form', [AdminAuthController::class, 'login'])->name('post_login');
        });

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/assure_login', [AdminAuthController::class, 'assureLogin'])->name('get_assure_login');
        });


        Route::name('2fa.')->prefix('2fa')->middleware(['auth:admin', '2fa_not_valid'])->group(function () {
            Route::get('/qr', [AdminAuthController::class, 'twoFactorAuthQr'])->name('twoFactorAuthQr');
            Route::get('/otp/{secret?}', [AdminAuthController::class, 'twoFactorAuthOTP'])->name('twoFactorAuthOTP');
            Route::post('/2fa', [AdminAuthController::class, 'validateOTP'])->name('valdiate');
        });



        Route::group(['middleware' => ['auth:admin', '2fa_valid']], function () {

            Route::get('login_attempts', [UsersController::class, 'loginAttempts'])->name('login_attempts');

            // Route::get('/assure_login', [AdminAuthController::class, 'assureLogin'])->name('get_assure_login');
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('post_logout');
            Route::get('/', [AdminDashboardController::class, 'index'])->name('get_dashboard');
            Route::resource('countries', CountriesController::class);
            Route::delete('countries', [CountriesController::class, 'batchDestroy'])->name('countries.batch_destroy');
            Route::resource('roles', RolesController::class);
            Route::delete('roles', [RolesController::class, 'batchDestroy'])->name('roles.batch_destroy');
            Route::resource('admins', AdminsController::class);
            Route::delete('admins', [AdminsController::class, 'batchDestroy'])->name('admins.batch_destroy');
            Route::resource('users', UsersController::class);
            Route::post('users/{user_id}/impersonate', [UsersController::class, 'impersonate'])->name('users.impersonate');
            Route::post('users/{user}/fake_payment', [UsersController::class, 'fakePayment'])->name('users.fake_payment');
            Route::delete('users', [UsersController::class, 'batchDestroy'])->name('users.batch_destroy');
            Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::resource('genres', GenresController::class);
            Route::delete('genres', [GenresController::class, 'batchDestroy'])->name('genres.batch_destroy');
            Route::resource('pages', PagesController::class);
            Route::delete('pages', [PagesController::class, 'batchDestroy'])->name('pages.batch_destroy');
            Route::resource('faqs', FaqsController::class);
            Route::delete('faqs', [FaqsController::class, 'batchDestroy'])->name('faqs.batch_destroy');
            Route::resource('guides', GuidesController::class);
            Route::delete('guides', [GuidesController::class, 'batchDestroy'])->name('guides.batch_destroy');
            Route::resource('subscriptions', SubscriptionsController::class);
            Route::delete('subscriptions', [SubscriptionsController::class, 'batchDestroy'])->name('subscriptions.batch_destroy');
            Route::resource('newsletter_emails', NewsletterEmailsController::class)->except(['edit', 'update']);
            Route::delete('newsletter_emails', [NewsletterEmailsController::class, 'batchDestroy'])->name('newsletter_emails.batch_destroy');
            Route::resource('social_links', SocialLinksController::class);
            Route::delete('social_links', [SocialLinksController::class, 'batchDestroy'])->name('social_links.batch_destroy');
            Route::resource('contact_messages', ContactMessagesController::class)->except(['edit', 'update', 'create', 'store']);
            Route::delete('contact_messages', [ContactMessagesController::class, 'batchDestroy'])->name('contact_messages.batch_destroy');
            Route::resource('publish_requests', PublishRequestsController::class)->only(['index', 'destroy']);
            Route::get('publish_requests/{publish_request}/approve', [PublishRequestsController::class, 'approvePublishRequest'])->name('publish_requests.approve_request');
            Route::delete('publish_requests', [PublishRequestsController::class, 'batchDestroy'])->name('publish_requests.batch_destroy');
            Route::resource('books', AdminBooksController::class);
            Route::delete('books', [AdminBooksController::class, 'batchDestroy'])->name('books.batch_destroy');
            Route::resource('books/{book}/book_editions', BookEditionsController::class);
            Route::delete('books/{book}/book_editions', [BookEditionsController::class, 'batchDestroy'])->name('book_editions.batch_destroy');
            Route::resource('occupations', OccupationsController::class);
            Route::delete('occupations', [OccupationsController::class, 'batchDestroy'])->name('occupations.batch_destroy');
            Route::resource('consults', ConsultsController::class)->only(['index', 'destroy']);
            Route::delete('consults', [ConsultsController::class, 'batchDestroy'])->name('consults.batch_destroy');
            Route::resource('blogs', BlogsController::class);
            Route::delete('blogs', [BlogsController::class, 'batchDestroy'])->name('blogs.batch_destroy');
            Route::get('blogs/{blog}/approve', [BlogsController::class, 'approvePost'])->name('blogs.approve_blog');
            Route::resource('plans', PlansController::class);
            Route::delete('plans', [PlansController::class, 'batchDestroy'])->name('plans.batch_destroy');
            Route::put('settings/{setting}', [SettingsController::class, 'update'])->name('settings.update');
            Route::get('tracing-email', [SettingsController::class, 'tracingEmail'])->name('settings.tracingEmail');
        });
    });
});

// Route::get('/mailable', function () {
//     $name = "Nahla";
    // $author_name = "Nahla";
    // $chapter_name = "Chapter 1";
    // $chapter_link = route('web.dashboard.books.editions.special_chapters.index', ['book' => 1, 'edition' => 1 ]);
    // $text = "Please make sure you are logged-in, if you want to delete <a href=$chapter_link><strong>$chapter_name</strong></a> special chapter, just click";
    // $link = route('web.dashboard.books.editions.special_chapters.delete_special_chapter', ['book' => 1, 'edition' => 1, 'special_chapter' => 1]);
    // $button = "<a href='$link' style='color: #63caf7; text-decoration: none'>Here</a>";
    // $company_name = "PenPeers";
    // $link = "https://www.google.com";
    // $edition_number = '2';
    // $book_title = "few";
    // $book_title = "few";
    // $book_description = "few";
    // $book_name = "few";
    // $co_author_name = "Nahla";
    // $participant_name = "Nahla";
    // $lead_author = "Nahla";
    // $lead_author_id = 1;
    // $author_link = "https://www.google.com";
    // $bookAuthorName = "Author";
    // $co_author_link = "https://www.google.com";
    // $participant_link = "https://www.google.com";
    // $participant_email = "https://www.google.com";
    // $book_link = "https://www.google.com";
    // $hashing_email = "https://www.google.com";
    // $register_text = "please register first from <a href={{route('web.get_register',['invitaion'=>'true','hashing'=>$hashing_email])}} >here</a>";
    // $slug = '1';
    // $book_id = 1;
//     $type = "byEmail";
//     $id = 1;

//     return view('mail.invitation-email', compact('name', 'book_title', 'book_description', 'author_link', 'bookAuthorName', 'book_link', 'hashing_email', 'type', 'id'));
// });
