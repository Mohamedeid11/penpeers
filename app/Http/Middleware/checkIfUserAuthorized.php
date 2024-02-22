<?php

namespace App\Http\Middleware;

use App\Models\BookParticipant;
use Closure;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkIfUserAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $book_id = BookParticipant::find($request->participant_id)->book_id;
        $leadBooksIds = Auth::user()->lead_books->pluck('id')->toArray();
        $coBooksIds = Auth::user()->co_books->pluck('id')->toArray();
        $reviewBooksIds = Auth::user()->review_books->pluck('id')->toArray();

        return (Auth::user() && (in_array($book_id, $leadBooksIds) || in_array($book_id, $coBooksIds) || in_array($book_id, $reviewBooksIds))? $next($request) : back());
    }
}
