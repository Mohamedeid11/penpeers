<?php

namespace App\Console\Commands;

use App\Mail\InformAuthorsDeletingBook;
use App\Models\Book;
use App\Notifications\RealTimeNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class DeletingBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting the book after 14 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $books = Book::all();

         foreach ($books as $book)
         {
             $now = Carbon::now();
             $diff = ($book->deleted_at != null) ? $now->diffInDays($book->deleted_at , false) : ' - ';
//             dump($diff . ' - ' . $book->title  );
             if ($diff === 1) {
//                 dump('the book have one day for delete' . ' - ' . $book->title . ' - ' . $book->deleted_at . ' - ' . $now . ' - ' . $diff);

                 $authors = $book->book_participants->pluck('user');

                 foreach ($authors as $author) {
                     Mail::to($author->email)->send(new InformAuthorsDeletingBook($book, $author));

                     $url =  URL::signedRoute('web.dashboard.books.authors.index' , $book->id);

                     $url_type = 'deleting_book';

                     $text = "This book ,<a style='color: #ce7852; text-decoration: underline' href= $url ><strong> $book->title </strong></a> , will be deleted on " . Carbon::parse($book->deleted_at)->toFormattedDateString();

                     $author->notify(new RealTimeNotification($text, $url, $url_type));
                 }

             }elseif($diff < 0 || $diff === 0){
//                 dump('delete the book' . ' - ' . $book->title  . ' - ' . $book->deleted_at . ' - '  . $now . ' - ' . $diff);
                $book->delete();
             }
         }

//         $delete_books = Book::where('deleted_at', '<=', Carbon::now())->delete();
//
//         if ($delete_books)
//         {
//
//             $this->info( 'Book deleted successfully');
//
//         }

    }
}
