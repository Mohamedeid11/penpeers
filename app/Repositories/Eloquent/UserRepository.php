<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookParticipant;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAllBooks(User $user): Collection
    {
        return $user->books()->orderBy('completed')->orderBy('created_at', 'desc')->get();
    }

    public function getAllBooksPublished(User $user): Collection
    {
        return $user->published_books()->get();
    }

    public function getAllBooksDraft(User $user): Collection
    {
        return $user->draft_books()->get();
    }

    public function getAllBooksCompleted(User $user): Collection
    {
        return $user->completed_books()->get();
    }

    public function getAllBooksRediting(User $user): Collection
    {
        return $user->rediting_books()->get();
    }

    public function getAllBooksShown(User $user): Collection
    {
        return $user->shown_books()->get();
    }

    public function getAllBooksHidden(User $user): Collection
    {
        return $user->hidden_books()->get();
    }

    public function getAllBooksContribution(User $user): Collection
    {
        return $user->co_books()->get();
    }

    public function getAllBooksContributors(User $user): Collection
    {
        $books_ids = $user->lead_books->pluck('id')->toArray();
        return BookParticipant::whereIn('book_id', $books_ids)->where('status', 1)->where('user_id', '!=', $user->id)->get();
    }

    public function getAllUserReceivedInvitations(User $user): Collection
    {
        return $user->invitations()->notALeadAuthor()->get();
    }

    public function getAllRegisteredAuthorsInvitations(User $user): Collection
    {
        $books_ids = $user->lead_books->pluck('id')->toArray();
        return BookParticipant::whereIn('book_id', $books_ids)->where('user_id', '!=', $user->id)->get();
    }

    public function getAllEmailInvitations(User $user): Collection
    {
        return $user->email_invitations()->get();
    }

    public function getBook(User $user, $book_id): ?Book
    {
        return $user->books()->where(['books.id' => $book_id])
            ->with([
                'book_special_chapters.authors', 'book_special_chapters.special_chapter',
                'book_chapters.authors'
            ])->firstOrFail();
    }
    public function getEdition(User $user, $book_id, $edition_num): ?BookEdition
    {
        $book = $this->getBook($user, $book_id);
        return $book->editions()->where(['edition_number' => $edition_num])->firstorFail();
    }
    public function getFirstEdition(User $user, $book_id, $edition_num): ?BookEdition
    {
        $edition = $this->getEdition($user, $book_id, $edition_num);
        if (!$edition) {
            $book = $this->getBook($user, $book_id);
            $book->editions()->save(new BookEdition([
                'edition_number' => 1,
                'original_price' => 0,
                'discount_price' => 0
            ]));
        }
        return $this->getEdition($user, $book_id, $edition_num);
    }
    public function search($query): Collection
    {
        return $this->model->where('email', 'like', '%' . $query . '%')->orWhere('username', 'like', '%' . $query . '%')->get();
    }

    public function searchByName($query,$interests): Collection
    {
        return $this->model->Where('name', 'like', '%' . $query . '%')
        ->whereHas('interests', function (Builder $q) use($interests) {
            $q->where(['interest_id' => $interests ]);
        })
        ->get();
    }
}
