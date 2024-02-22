<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAllBooks(User $user): Collection;
    public function getAllBooksPublished(User $user): Collection;
    public function getAllBooksDraft(User $user): Collection;
    public function getAllBooksCompleted(User $user): Collection;
    public function getAllBooksShown(User $user): Collection;
    public function getAllBooksHidden(User $user): Collection;
    public function getAllBooksContribution(User $user): Collection;
    public function getAllBooksContributors(User $user): Collection;
    public function getAllUserReceivedInvitations(User $user): Collection;
    public function getAllBooksRediting(User $user): Collection;
    public function getAllRegisteredAuthorsInvitations(User $user): Collection;
    public function getAllEmailInvitations(User $user): Collection;

    public function getEdition(User $user, $book_id, $edition_num): ?BookEdition;
    public function getBook(User $user, $book_id): ?Book;
    public function getFirstEdition(User $user, $book_id, $edition_num): ?BookEdition;
    public function search($query): Collection;
    public function searchByName($query,$interests): Collection;

}
