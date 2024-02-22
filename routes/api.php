<?php

use App\Http\Controllers\Admin\AdminBooksController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinksController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Web\Dashboard\AccountController;
use App\Http\Controllers\Web\Dashboard\DashboardAuthorsController;
use App\Http\Controllers\Web\Dashboard\DashboardBooksController;
use App\Http\Controllers\Web\Dashboard\DashboardChaptersController;
use App\Http\Controllers\Web\Dashboard\DashboardSpecialChaptersController;
use App\Http\Controllers\Web\UserAuthController;
use App\Http\Controllers\Web\LandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::get('/plans/{account_type}', [UserAuthController::class, 'getPlansByAccountType'])->name('api.getPlansByType');
Route::get('users/{id}', [UsersController::class, 'getUser'])->name('api.get_user_info');

Route::group(['middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {

    Route::name('admin.api.')->prefix('admin')->middleware('auth:admin_api')->group(function () {
        Route::post('countries/toggle_active/{country_id}', [CountriesController::class, 'toggle_active'])->name('countries.toggle_active');
        Route::post('users/toggle_active/{user_id}', [UsersController::class, 'toggle_active'])->name('users.toggle_active');
        Route::post('social_links/toggle_active/{social_link_id}', [SocialLinksController::class, 'toggle_active'])->name('social_links.toggle_active');
        Route::post('books/toggle_popular/{book_id}', [AdminBooksController::class, 'toggle_popular'])->name('books.toggle_popular');
        Route::post('plans/toggle_active/{plan_id}', [\App\Http\Controllers\Admin\PlansController::class, 'toggle_active'])->name('plans.toggle_active');

    });

    Route::name('web.api.')->middleware('auth:user_api')->group(function () {
        Route::name('dashboard.')->prefix('dashboard')->group(function () {
            Route::get('authors/search', [DashboardBooksController::class, 'searchAuthors'])->name('authors.search');
            Route::get('books/{book}/chapters/{chapter}/author-role', [DashboardAuthorsController::class, 'checkChapterAuthorRole'])->name('chapter.author_role');
            Route::get('authors/search/by_name', [DashboardBooksController::class, 'searchAuthorsByName'])->name('authors.search.byname');
            Route::put('dashboard/books/{book}/editions/{edition}/chapters/{chapter}/content', [DashboardChaptersController::class, 'updateContent'])
                ->name('books.editions.chapters.update');
            Route::put('dashboard/books/{book}/editions/{edition}/special_chapters/{special_chapter}/content', [DashboardSpecialChaptersController::class, 'updateContent'])
                ->name('books.editions.special_chapters.update');

        });
        Route::post('account/payment/prepare', [AccountController::class, 'preparePayment'])->name('account.preparePayment');
        // Route::post('account/payment/prepare/credi', [AccountController::class, 'preparePaymentCredi'])->name('account.preparePayment.crediMax');
    });
    Route::get('preview-book/{book}/edition/{edition}/', [LandingController::class,'previewBookChapter'])->name('api.preview.book');
});
