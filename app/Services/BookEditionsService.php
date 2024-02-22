<?php

namespace App\Services;

use App\Models\BookEdition;
use App\Repositories\Interfaces\BookEditionRepositoryInterface;
use App\Repositories\Interfaces\BookPublishRequestRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookEditionsService
{
    protected $userRepository;
    protected $genresRepository;
    protected $specialChaptersRepository;
    protected $bookRepository;
    protected $bookRoleRepository;
    protected $bookPublishRequestRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        GenreRepositoryInterface $genresRepository,
        SpecialChapterRepositoryInterface $specialChaptersRepository,
        BookRepositoryInterface $bookRepository,
        BookRoleRepositoryInterface $bookRoleRepository,
        BookPublishRequestRepositoryInterface $bookPublishRequestRepository
    ) {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
        $this->specialChaptersRepository = $specialChaptersRepository;
        $this->bookRepository = $bookRepository;
        $this->bookRoleRepository = $bookRoleRepository;
        $this->bookPublishRequestRepository = $bookPublishRequestRepository;
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

    public function addEdition($request, $book_id)
    {
        $user = auth()->user();
        $book = $this->bookRepository->get($book_id);
        $next_num = $book->editions()->orderBy('edition_number', 'desc')->first()->edition_number + 1;

        $front_cover = $request->file('front_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']);
        if ($request->filled('front_cover_crop')) {
            crop($front_cover, $request->input('front_cover_crop'));
        }

        $back_cover = $request->file('back_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']);
        if ($request->filled('back_cover_crop')) {
            crop($back_cover, $request->input('back_cover_crop'));
        }

        $fixed = [
            'edition_number' => $next_num,
            'visibility' => 'shared',
            'original_price' => 0,
            'discount_price' => 0,
            'front_cover' => $front_cover,
            'back_cover' => $back_cover
        ];

        return $book->editions()->create(array_merge($request->all(), $fixed));
    }

    // public function addEdition($book_id)
    // {
    //     $book = $this->bookRepository->get($book_id);
    //     $next_num = $book->editions()->orderBy('edition_number', 'desc')->first()->edition_number + 1;

    //     $fixed = [
    //         'edition_number' => $next_num,
    //         'visibility' => 'shared',
    //         'original_price' => 0,
    //         'discount_price' => 0
    //     ];

    //     return $book->editions()->create($fixed);
    // }

    public function UpdateEdition($request, $edition)
    {
        $user = auth()->user();
        $front_cover = $request->hasFile('front_cover') ?
            $request->file('front_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']) :
            $edition->front_cover;
        if ($request->filled('front_cover_crop')) {
            crop($front_cover, $request->input('front_cover_crop'));
        }
        $back_cover = $request->hasFile('back_cover') ?
            $request->file('back_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']) :
            $edition->back_cover;
        if ($request->filled('back_cover_crop')) {
            crop($back_cover, $request->input('back_cover_crop'));
        }
        $edition->update(array_merge(
            $request->only(['title', 'description', 'language', 'genre_id']),
            ['front_cover' => $front_cover, 'back_cover' => $back_cover]
        ));
    }

    public function publishEdition($book, $edition)
    {
        $chapters_completed = $book->book_chapters()->where('book_edition_id', $edition->id)->completed()->count();
        $chapters =  $book->book_chapters()->where('book_edition_id', $edition->id)->count();
        if($chapters_completed != $chapters)
        {
            return false;
        }

        $special_chapters = $book->book_special_chapters()->where('book_edition_id', $edition->id)->completed()->count();
        $special_chapters_compelted =  $book->book_special_chapters()->where('book_edition_id', $edition->id)->count();

        if($special_chapters_compelted != $special_chapters)
        {
            return false;
        }

        $edition->update([ 'status' => 1 , 'status_changed_at' => Carbon::now()->format('Y-m-d')]);
        return $edition;
    }

    public function reeditEdition($book, $edition)
    {
        $edition->update([ 'status' => 2 , 'status_changed_at' => Carbon::now()->format('Y-m-d'), 'is_hidden' => false, 'is_hidden_changed_at' => date('Y-m-d')]);
        return $edition;
    }



    // public function publishEdition($book_id, $edition_id)
    // {
    //     Log::info($book_id);
    //     Log::info($edition_id);
    //     $this->bookPublishRequestRepository->firstOrCreate(
    //         ['book_edition_id' => $edition_id],
    //         [
    //             'book_id' => $book_id,
    //             'user_id' => auth()->id(),
    //             'approved' => false,
    //             'publication_date' => Carbon::now()->format('Y-m-d H:i:s')
    //         ]
    //     );
    // }

    public  function toggleEdition(BookEdition $edition)
    {
        // $edition__new_hidden = $edition->is_hidden ? 0 : 1;
        $edition->is_hidden = !$edition->is_hidden;
        $edition->is_hidden_changed_at = now();
        $edition->save();
    }

    public function deleteEdition($edition)
    {
        return $edition->delete();
    }
}
