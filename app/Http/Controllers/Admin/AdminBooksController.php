<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Books\CreateBook;
use App\Http\Requests\Admin\Books\DeleteBook;
use App\Http\Requests\Admin\Books\EditBook;
use App\Models\Book;
use App\Models\BookParticipant;
use App\Services\Admin\AdminBooksCrudService;
use Illuminate\Http\Request;

class AdminBooksController extends BaseAdminController
{
    private $adminBooksCrudService;
    public function __construct(AdminBooksCrudService $adminBooksCrudService)
    {
        parent::__construct(Book::class);
        // $this->authorizeResource(Book::class);
        $this->adminBooksCrudService = $adminBooksCrudService; 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = $this->adminBooksCrudService->listAllBooks();
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book = new Book;
        return view('admin.books.create-edit', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBook $request)
    {   
        $this->adminBooksCrudService->createBook($request);
        return redirect(route('admin.books.index'))->with('success', 'Book Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('admin.books.view-book', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $book->author_id = BookParticipant::leadAuthor($book->id)->first()->user_id;
        return view('admin.books.create-edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(EditBook $request, Book $book)
    {   
        // dd($request);
        $this->adminBooksCrudService->editBook($request, $book);
        return redirect(route('admin.books.index'))->with('success', 'Book Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBook $request, Book $book)
    {
        $this->adminBooksCrudService->deleteBook($book);
        return redirect(route('admin.books.index'));
    }

    public function batchDestroy(DeleteBook $request){
        $this->adminBooksCrudService->batchDeleteBooks($request->all());
        return redirect(route('admin.books.index'));
    }

    public function toggle_popular($book_id){
        $this->authorize('viewAny', Book::class);
        $book = Book::findOrFail($book_id);
        $book->popular = !$book->popular;
        $book->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }
}
