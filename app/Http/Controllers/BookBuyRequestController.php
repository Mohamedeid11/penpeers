<?php

namespace App\Http\Controllers;

use App\Models\BookBuyRequest;
use App\Http\Requests\StoreBookBuyRequestRequest;
use App\Http\Requests\UpdateBookBuyRequestRequest;

class BookBuyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreBookBuyRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookBuyRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookBuyRequest  $bookBuyRequest
     * @return \Illuminate\Http\Response
     */
    public function show(BookBuyRequest $bookBuyRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookBuyRequest  $bookBuyRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(BookBuyRequest $bookBuyRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookBuyRequestRequest  $request
     * @param  \App\Models\BookBuyRequest  $bookBuyRequest
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookBuyRequestRequest $request, BookBuyRequest $bookBuyRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookBuyRequest  $bookBuyRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookBuyRequest $bookBuyRequest)
    {
        //
    }
}
