<?php
namespace App\Services;

use App\Http\Requests\Web\Dashboard\Chapters\UpdateChapterRequest;
use App\Models\User;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class BookChaptersService
{
    protected $userRepository;
    protected $genresRepository;
    protected $specialChaptersRepository;
    protected $bookRepository;
    protected $bookRoleRepository;
    public function __construct(UserRepositoryInterface $userRepository, GenreRepositoryInterface $genresRepository,
                                SpecialChapterRepositoryInterface $specialChaptersRepository,
                                BookRepositoryInterface $bookRepository,
                                BookRoleRepositoryInterface $bookRoleRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
        $this->specialChaptersRepository = $specialChaptersRepository;
        $this->bookRepository = $bookRepository;
        $this->bookRoleRepository = $bookRoleRepository;
    }
    public function getBook($book_id){
        return $this->userRepository->getBook(auth()->user(), $book_id);
    }
    public function addChapter($request, $book_id, $edition_num){
        return $this->bookRepository->addChapter($request->all(), $book_id, $edition_num);
    }
    public function getEdition($book_id, $edition_num){
        return $edition_num == 1 ? $this->userRepository->getFirstEdition(auth()->user(), $book_id, $edition_num) : $this->userRepository->getEdition(auth()->user(), $book_id, $edition_num);
    }

    public function updateChapter(UpdateChapterRequest $request, $book_id, $edition_number, $chapter_id)
    {
        $book = $this->getBook($book_id);
        $edition = $this->getEdition($book_id, $edition_number);
        $book_chapter = $edition->chapters()->where(['id'=>$chapter_id])->firstOrFail();
        $book_chapter->update($request->only(['name', 'deadline', 'order']));
        //->sync([User::find($request->input('author_id'))->id])
        $book->book_chapter_authors()->where(['book_chapter_id' => $book_chapter->id])->where(['book_edition_id' => $edition->id]);

    }
}
