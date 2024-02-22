<?php

namespace App\Http\Middleware;

use App\Models\Book;
use App\Models\BookEdition;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class editionPublished
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
        $params = $request->route()->parameters();
        $book_id = $params['book'];
        $edition_number = $params['edition'];
        $disallowed_routes_names = 0;

        if ($book_id  && $edition_number) {
            $bookEdition = BookEdition::where('book_id', $book_id)->where('edition_number', $edition_number)->first();
            $edition_status = $bookEdition ? $bookEdition->status : null;
            // if((Route::is('web.dashboard.books.editions.special_chapters.index') || Route::is('web.dashboard.books.editions.chapters.index'))){
            //     $disallowed_routes_names = 0;
            // }
            // else
            if ((Route::is('web.dashboard.books.editions.special_chapters.*') || Route::is('web.dashboard.books.editions.chapters.*'))) {
                $disallowed_routes_names = TRUE;
            }

            if ( $edition_status == 1 &&  $disallowed_routes_names) {
                // abort(403);
                return redirect(route('web.dashboard.books.editions.edition_settings', ['book' => request()->book, 'edition' => $bookEdition]));
            }
        }

        return $next($request);
    }
}
