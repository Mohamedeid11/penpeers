<?php
namespace App\Services\Admin;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookRole;
use App\Models\User;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AdminBooksCrudService {

    protected $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function listAllBooks(){
        return $this->bookRepository->paginate(100);
    }

    public function createBook($request){
        $front_cover = $request->file('front_cover')->store('uploads/'.$request->author_id.'/books/', ['disk'=>'public']);
        $back_cover = $request->file('back_cover')->store('uploads/'.$request->author_id.'/books/', ['disk'=>'public']);
        $book = Book::create(array_merge($request->only(['title', 'description', 'language', 'genre_id', 'visibility']), ['front_cover'=>$front_cover, 'back_cover'=>$back_cover]));
        $book->participants()->attach($request->author_id,
            ['book_role_id'=>BookRole::where(['name'=>'lead_author'])->first()->id , 'status' => 1]);
        $book->editions()->save(new BookEdition([
            'edition_number'=>1,
            'original_price'=>0,
            'discount_price'=>0
        ]));
    }

    public function editBook($request, Book $book){
        $user = User::find($request->author_id);
        if (!$book->book_participants->contains( 'user_id',$user->id)){
            abort(403);
        }
        $front_cover = $request->hasFile('front_cover') ?
            $request->file('front_cover')->store('uploads/'.$user->id.'/books/', ['disk'=>'public']):
            $book->front_cover;

        $back_cover = $request->hasFile('back_cover') ?
            $request->file('back_cover')->store('uploads/'.$user->id.'/books/', ['disk'=>'public']):
            $book->back_cover;

        $book->update(array_merge($request->only(['title', 'description', 'language', 'genre_id', 'visibility']),
            ['front_cover'=>$front_cover, 'back_cover'=>$back_cover]));
    }

    public function deleteBook(Book $book){
        $book->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.book')]) );
    }

    public function batchDeleteBooks(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->bookRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.books')]) );
    }
}
