<?php
namespace App\Services\Admin;

use App\Models\Book;
use App\Models\BookEdition;
use App\Repositories\Interfaces\BookEditionRepositoryInterface;

class AdminBookEditionsCrudService {
    private $bookEditionRepository;
    public function __construct(BookEditionRepositoryInterface $bookEditionRepository){
        $this->bookEditionRepository = $bookEditionRepository;
    }

    public function listAllBookEditions(Book $book){
        return $book->editions()->paginate(100);
    }

    public function addEdition($request, Book $book){
        $next_num = $book->editions()->orderBy('edition_number', 'desc')->first()->edition_number + 1;
        return $book->editions()->create(array_merge($request->all(), ['edition_number'=>$next_num]));
    }

    public function editEdition($request, BookEdition $bookEdition){
        return $bookEdition->update($request->all());
    }

    public function deleteBookEdition(BookEdition $bookEdition)
    {
        $bookEdition->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.edition')]) );
    }

    public function batchDeleteBookEditions(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->bookEditionRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.editions')]) );
    }
}