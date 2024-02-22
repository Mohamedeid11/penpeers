<?php

namespace App\Services;

use App\Mail\InformAuthorsDeletingBook;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookRole;
use App\Models\BookSpecialChapter;
use App\Models\SpecialChapter;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorBooksService
{
    protected $userRepository;
    protected $genresRepository;
    public function __construct(UserRepositoryInterface $userRepository, GenreRepositoryInterface $genresRepository)
    {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
    }

    public function listAll()
    {
        return $this->userRepository->getAllBooks(auth()->user());
    }

    public function listAllPublished()
    {
        return  $this->userRepository->getAllBooksPublished(auth()->user());
    }

    public function listAllDraft()
    {
        return $this->userRepository->getAllBooksDraft(auth()->user());
    }

    public function listAllCompleted()
    {
        return $this->userRepository->getAllBooksCompleted(auth()->user());
    }

    public function listAllShown()
    {
        return $this->userRepository->getAllBooksShown(auth()->user());
    }

    public function listAllHidden()
    {
        return $this->userRepository->getAllBooksHidden(auth()->user());
    }

    public function listAllReditingBooks()
    {
        return $this->userRepository->getAllBooksRediting(auth()->user());
    }

    public function listAllContribution()
    {
        return $this->userRepository->getAllBooksContribution(auth()->user());
    }

    public function listAllContributors()
    {
        return $this->userRepository->getAllBooksContributors(auth()->user());
    }

    public function getAllUserReceivedInvitations()
    {
        return $this->userRepository->getAllUserReceivedInvitations(auth()->user());
    }

    public function getAllRegisteredAuthorsInvitations()
    {
        return $this->userRepository->getAllRegisteredAuthorsInvitations(auth()->user());
    }

    public function getAllEmailInvitations()
    {
        return $this->userRepository->getAllEmailInvitations(auth()->user());
    }

    public function listGenres()
    {
        return $this->genresRepository->all();
    }
    public function create($request)
    {
        $user = auth()->user();
        if ($request->has('front_cover_revertImage') || !$request->hasFile('front_cover') )
        {
            $front_cover = 'uploads/' . $user->id . '/books/default_front.png';
            if(!Storage::disk('public')->exists($front_cover))
            {
                Storage::disk('public')->copy('defaults/front.png' , $front_cover);
            }

        }else{

            $front_cover = $request->file('front_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']);
            if ($request->filled('front_cover_crop')) {
                $front_cover_image = $request->front_cover_crop;  // your base64 encoded
                $front_cover_image = str_replace('data:image/png;base64,', '', $front_cover_image);
                $front_cover_image = str_replace(' ', '+', $front_cover_image);
                $front_cover_imageName = \Illuminate\Support\Carbon::now()->timestamp . '_front_cover_crop_' . $user->id .'.' . 'png';
                $front_cover = "uploads/" . $user->id . "/books/". $front_cover_imageName;
                Storage::disk('public')->put($front_cover, base64_decode($front_cover_image));
            }
        }


//        if ($request->filled('front_cover_crop')) {
//            crop($front_cover, $request->input('front_cover_crop'));
//        }

        if ($request->has('back_cover_revertImage') || !$request->hasFile('back_cover') )
        {
            $back_cover = 'uploads/' . $user->id . '/books/default_back.png';
            if(!Storage::disk('public')->exists($back_cover))
            {
                Storage::disk('public')->copy('defaults/back.png' , $back_cover);
            }

        }else{

            $back_cover = $request->file('back_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']);
            if ($request->filled('back_cover_crop')) {
                $back_cover_image = $request->back_cover_crop;  // your base64 encoded
                $back_cover_image = str_replace('data:image/png;base64,', '', $back_cover_image);
                $back_cover_image = str_replace(' ', '+', $back_cover_image);
                $back_cover_imageName = \Illuminate\Support\Carbon::now()->timestamp . '_back_cover_crop_' . $user->id . '.' . 'png';
                $back_cover = "uploads/" . $user->id . "/books/". $back_cover_imageName;
                Storage::disk('public')->put($back_cover, base64_decode($back_cover_image));
            }
        }


//        if ($request->filled('back_cover_crop')) {
//            crop($back_cover, $request->input('back_cover_crop'));
//        }


        $receive_requests = $request->receive_requests == 'true' ? 1 : 0;
        $book = Book::create(array_merge(
            $request->only(['title', 'price' ,'description', 'language', 'genre_id']),
            ['front_cover' => $front_cover, 'back_cover' => $back_cover, 'receive_requests' => $receive_requests]
        ));
        $book->participants()->attach(
            $user->id,
            ['book_role_id' => BookRole::where(['name' => 'lead_author'])->first()->id, 'status' => 1]
        );
        $book_edition = $book->editions()->save(new BookEdition([
            'edition_number' => 1,
            'original_price' => 0,
            'discount_price' => 0
        ]));
        $book_special_chapter = BookSpecialChapter::create([
            'book_id' => $book->id,
            'book_edition_id' => $book_edition->id,
            'special_chapter_id' => SpecialChapter::where('name', 'intro')->first()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('book_special_chapter_authors')->insert([
            'book_id' => $book->id,
            'book_edition_id' => $book_edition->id,
            'user_id' => $book->lead_author->id,
            'book_special_chapter_id' => $book_special_chapter->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
        return $book;
    }
    public function getBook($book_id)
    {
        return $this->userRepository->getBook(auth()->user(), $book_id);
    }

    public function deleteBook($book )
    {
        return $book->delete();
    }
    public function cancelDeletingBook($book )
    {
        $authors = $book->book_participants->pluck('user');

        foreach ($authors as $author) {
            Mail::to($author->email)->send(new InformAuthorsDeletingBook($book, $author , 'cancel_deleting_book'));
            if ($author->id != $book->lead_author->id)
            {
                $url = route('web.dashboard.books.authors.index' ,  $book->id);

                $url_type = 'cancel_deleting_book';

                $text = $book->lead_author->name . " has cancelled the deletion of the book <a style='color: #ce7852; text-decoration: underline' href= $url ><strong> $book->title </strong></a> .";

                $author->notify(new RealTimeNotification( $text , $url , $url_type));

            }
        }

        return $book->update(['deleted_at' => null]);

    }

    public function completeBook($book)
    {
        $book->update([
            'completed' => true,
            'editing_status_changed_at' => date('Y-m-d'),
            'visibility' => 'public'
        ]);

        // $book->editions()->update([
        //     'is_hidden' => true,
        //     'publication_date' => date('Y-m-d'),
        //     'visibility' => 'public'
        // ]);

        return true;
    }
    public function getEdition($book_id, $edition_num)
    {
        return $edition_num == 1 ? $this->userRepository->getFirstEdition(auth()->user(), $book_id, $edition_num) : $this->userRepository->getEdition(auth()->user(), $book_id, $edition_num);
    }
    public function search($query)
    {
        $query = Str::of($query)->replaceMatches('/^@/', '')->__toString();
        return $this->userRepository->search($query);
    }
    public function searchByName($query, $interests)
    {
        return $this->userRepository->searchByName($query, $interests);
    }

    public function update($request, $book)
    {
        $user = auth()->user();
        if (!$book->participants->contains($user)) {
            abort(403);;
        }
        if ($request->filled('front_cover_crop') )
        {
            Storage::disk('public')->delete($book->front_cover);
        }
        if($request->filled('back_cover_crop'))
        {
            Storage::disk('public')->delete($book->back_cover);
        }

        $front_cover = $request->hasFile('front_cover') ?
            $request->file('front_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']) :
            $book->front_cover;

        if ($request->has('front_cover_revertImage') )
        {
            $front_cover = 'uploads/' . $user->id . '/books/default_front.png';
            if(!Storage::disk('public')->exists($front_cover))
            {
                Storage::disk('public')->copy('defaults/front.png' , $front_cover);
            }

        }else{
            if ($request->filled('front_cover_crop')) {
                $front_cover_image = $request->front_cover_crop;  // your base64 encoded
                $front_cover_image = str_replace('data:image/png;base64,', '', $front_cover_image);
                $front_cover_image = str_replace(' ', '+', $front_cover_image);
                $front_cover_imageName = \Illuminate\Support\Carbon::now()->timestamp . '_front_cover_crop_' . $user->id .'.' . 'png';
                $front_cover = "uploads/" . $user->id . "/books/". $front_cover_imageName;
                Storage::disk('public')->put($front_cover, base64_decode($front_cover_image));
            }
        }

//        if ($request->filled('front_cover_crop')) {
//            crop($front_cover, $request->input('front_cover_crop'));
//        }
        $back_cover = $request->hasFile('back_cover') ?
            $request->file('back_cover')->store('uploads/' . $user->id . '/books/', ['disk' => 'public']) :
            $book->back_cover;

        if ($request->has('back_cover_revertImage') )
        {
            $back_cover = 'uploads/' . $user->id . '/books/default_back.png';
            if(!Storage::disk('public')->exists($back_cover))
            {
                Storage::disk('public')->copy('defaults/back.png' , $back_cover);
            }

        }else{
            if ($request->filled('back_cover_crop')) {
                $back_cover_image = $request->back_cover_crop;  // your base64 encoded
                $back_cover_image = str_replace('data:image/png;base64,', '', $back_cover_image);
                $back_cover_image = str_replace(' ', '+', $back_cover_image);
                $back_cover_imageName = \Illuminate\Support\Carbon::now()->timestamp . '_back_cover_crop_' . $user->id . '.' . 'png';
                $back_cover = "uploads/" . $user->id . "/books/". $back_cover_imageName;
                Storage::disk('public')->put($back_cover, base64_decode($back_cover_image));
            }
        }

//        if ($request->filled('back_cover_crop')) {
//            crop($back_cover, $request->input('back_cover_crop'));
//        }
        $receive_requests = $request->receive_requests == 'true' ? 1 : 0;
        $book->update(array_merge(
            $request->only(['title', 'description', 'price' , 'language', 'genre_id', 'visibility']),
            ['front_cover' => $front_cover, 'back_cover' => $back_cover, 'receive_requests' => $receive_requests]
        ));
    }

    public function toggleBookVisibiltyStatus($book){
        $book->update([
            'status' => ! $book->status,
            'status_changed_at' => date('Y-m-d')
        ]);
    }

    public function reditBook($book){
        $book->update([
            'completed' => 2,
            'editing_status_changed_at' => date('Y-m-d'),
            'status' => 0,
            'status_changed_at' => date('Y-m-d'),
            'visibility' => 'private'
        ]);

        // $book->editions()->update([
        //     'is_hidden' => false,
        //     'publication_date' => null,
        //     'status' => 2,
        //     'status_changed_at' => date('Y-m-d'),
        //     'visibility' => 'private'
        // ]);
    }
}
