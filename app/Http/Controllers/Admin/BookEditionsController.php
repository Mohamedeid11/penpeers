<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Editions\AddBookEdition;
use App\Http\Requests\Admin\Editions\DeleteBookEdition;
use App\Http\Requests\Admin\Editions\EditBookEdition;
use App\Models\Book;
use App\Models\BookEdition;
use App\Services\Admin\AdminBookEditionsCrudService;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Parent_;

class BookEditionsController extends Controller
{
    protected $bookEditionsCrudService;

    public function __construct(AdminBookEditionsCrudService $bookEditionsCrudService)
    {
        $this->authorizeResource(BookEdition::class);
        $this->bookEditionsCrudService = $bookEditionsCrudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        $editions = $this->bookEditionsCrudService->listAllBookEditions($book);
        return view('admin.editions.index', compact('editions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Book $book)
    {
        $book_edition = new BookEdition();
        return view('admin.editions.create-edit', compact('book_edition'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBookEdition $request, Book $book)
    {
        $this->bookEditionsCrudService->addEdition($request, $book);
        return redirect(route('admin.book_editions.index', ['book'=>$book->id]))->with('success', 'Edition Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookEdition  $edition
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book, BookEdition $edition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookEdition  $book_edition
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book, BookEdition $book_edition)
    {
        return view('admin.editions.create-edit', compact('book_edition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookEdition  $edition
     * @return \Illuminate\Http\Response
     */
    public function update(EditBookEdition $request, Book $book, BookEdition $edition)
    {
        $this->bookEditionsCrudService->editEdition($request, $edition);
        return redirect(route('admin.book_editions.index', ['book'=>$book->id]))->with('success', 'Edition Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookEdition  $edition
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBookEdition $request, Book $book, BookEdition $book_edition)
    {
        $this->bookEditionsCrudService->deleteBookEdition($book_edition);
        return back();
    }

    public function batchDestroy(DeleteBookEdition $request)
    {
        $this->bookEditionsCrudService->batchDeleteBookEditions($request->all());
        return back();
    }
}
