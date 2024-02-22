<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class BookSpecialChaptersService
{
    protected $userRepository;
    protected $genresRepository;
    protected $specialChaptersRepository;
    protected $bookRepository;
    protected $bookRoleRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        GenreRepositoryInterface $genresRepository,
        SpecialChapterRepositoryInterface $specialChaptersRepository,
        BookRepositoryInterface $bookRepository,
        BookRoleRepositoryInterface $bookRoleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
        $this->specialChaptersRepository = $specialChaptersRepository;
        $this->bookRepository = $bookRepository;
        $this->bookRoleRepository = $bookRoleRepository;
    }
    public function getBook($book_id)
    {
        return $this->userRepository->getBook(auth()->user(), $book_id);
    }
    public function getEdition($book_id, $edition_num)
    {
        return $edition_num == 1 ? $this->userRepository->getFirstEdition(auth()->user(), $book_id, $edition_num) : $this->userRepository->getEdition(auth()->user(), $book_id, $edition_num);
    }
    public function getSpecialChapters()
    {
        return $this->specialChaptersRepository->all();
    }
    public function addSpecialChapter($request, $book_id, $edition_num)
    {
        return $this->bookRepository->addSpecialChapter($request->all(), $book_id, $edition_num);
    }

    public function updateSPecialChapter($request, $book_id, $edition_number, $special_chapter_id)
    {
        $book = $this->getBook($book_id);
        $edition = $this->getEdition($book_id, $edition_number);
        $book_chapter = $edition->special_chapters()->where(['id' => $special_chapter_id])->firstOrFail();

        $book_chapter->update($request->only(['deadline']));
        // dd($request);
        //->sync([User::find($request->input('author_id'))->id])
        $book->special_chapters_authors()->where(['book_chapter_id' => $book_chapter->id])->where(['book_edition_id' => $edition->id]);
    }
}
