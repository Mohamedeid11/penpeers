<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       $books = Book::take(5)->get();
       foreach ($books as $book){
        // Update Book to be has scope Completed
        $book->book_special_chapters->each(function ($chapter) {
            $chapter->update(['completed'=>1 , 'completed_at' => now()]);
        });

        $book->book_chapters->each(function ($chapter) {
            $chapter->update(['completed'=>1 , 'completed_at' => now()]);
        });

        $book->update(array('status'=> 1 , 'completed'=>1, 'editing_status_changed_at' => now() ));
        //get book reviews users id which reviewd on it
        $user_ids = BookReview::where('book_id', $book->id)->pluck('user_id');

        $users = User::whereNotIn('id',$user_ids)->where('id', '!=', $book->lead_author->id)->get();
        // dd($user_id,$book->id);
        foreach($users as $user){
            BookReview::factory()->create([
                'book_id' => $book->id,
                'user_id' =>  $user->id
            ]);
        }

       }

    }
}
