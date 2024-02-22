<?php

namespace App\Http\Middleware;

use App\Models\Book;
use App\Models\BookSpecialChapter;
use Closure;
use Database\Seeders\BookSpecialChapterAuthorSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class LimitCoAUthorActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authed_user = Auth::user();

        $params = $request->route()->parameters();
        $route_method = $request->route()->methods()[0];

        $book_id = array_key_exists('book', $params) ? $params['book'] : 0;
        $special_chapter_id = array_key_exists('special_chapter', $params) ? $params['special_chapter'] : 0;
        $chapter_id = array_key_exists('chapter', $params) ? $params['chapter'] : 0;
        if ($book_id) {

            $book = is_string($book_id) ?  Book::find($book_id) : $book_id;

            $book_author_id = $book->lead_author->id;

            if ($book_author_id != $authed_user->id) {

                if ($route_method == "GET") {
                    return  $next($request);
                } else {

                    if (Route::is('web.dashboard.books.editions.special_chapters.completed_email')) {
                        $book_seprcial_chapter_author = DB::table('book_special_chapter_authors')
                            ->where('book_special_chapter_id', $special_chapter_id->id)
                            ->where('user_id', $authed_user->id)
                            ->exists();
                        if ($book_seprcial_chapter_author) {
                            return $next($request);
                        }
                    }

                    if (Route::is('web.dashboard.books.editions.chapters.completed_email')) {
                        $book_chapter_author = DB::table('book_chapter_authors')->where('book_chapter_id', $chapter_id->id)
                            ->where('user_id', $authed_user->id)
                            ->exists();
                        if ($book_chapter_author) {
                            return $next($request);
                        }
                    }

                    return redirect()->back()->with('error', __('middleware.limit_co-author') );
                }
            }

            return $next($request);
        }
        return $next($request);
    }
}
