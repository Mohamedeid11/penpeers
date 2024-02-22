<?php

namespace App\Http\Middleware;

use App\Models\Book;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class BookCompletedActions
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
        $route_method = $request->route()->methods()[0];

        $book_id = array_key_exists('book', $params) ? $params['book'] : 0;

        if ($book_id) {

            $book = is_string($book_id)?  Book::find($book_id): $book_id;
            // dd($book,$book_id);
            $completed = $book->completed();
            // dd($book->completed(),$completed);
            if ($completed) {
                if ($route_method == "GET") {
                    return  $next($request);
                } else {
                    return redirect()->back()->with('error', __('middleware.book_completed.error') );
                }
            }

            return $next($request);
        }
        return $next($request);
    }
}
