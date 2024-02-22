<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookSpecialChapter;
use App\Models\SpecialChapter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class BookSpecialChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chapters = [];
        $books_ids = Book::pluck('id')->toArray();
        foreach($books_ids as $book_id){
            $book_editions_ids = BookEdition::where('book_id', $book_id)->pluck('id')->toArray();
            $special_chapter_ids = SpecialChapter::inRandomOrder()->limit(2)->pluck('id')->toArray();
            $book = Book::where('id' ,$book_id)->first();
            if ($book->title == "Living In The Light" ) {

                $content = File::get(base_path()."/database/assets/books/LivingInTheLight/introduction.html");

            }elseif($book->title == "Realistic Drawing Secrets") {

                $content = File::get(base_path()."/database/assets/books/RealisticDrawingSecrets/introduction.html");

            }elseif($book->title == "War of Dragon") {

                $content = File::get(base_path()."/database/assets/books/warOfDragon/introduction.html");

            }elseif($book->title == "The Wild Beauty") {

                $content = File::get(base_path()."/database/assets/books/TheWildBeauty/introduction.html");

            }elseif($book->title == "Mystery of Universe") {

                $content = File::get(base_path()."/database/assets/books/MysteryofUniverse/introduction.html");

            }elseif($book->title == "Memorise") {

                $content = File::get(base_path()."/database/assets/books/Memorise/Introduction.html");

            }elseif($book->title == "Our World Our Life") {

                $content = File::get(base_path()."/database/assets/books/OurWorldOurLife/introduction.html");

            }elseif($book->title == "Art Of Illustrator") {

                $content = File::get(base_path()."/database/assets/books/ArtOfIllustrator/introduction.html");

            }elseif($book->title == "Magazine") {

                $content = File::get(base_path()."/database/assets/books/Magazine/introduction.html");

            }elseif($book->title == "حياة الصحراء") {

            $content = File::get(base_path()."/database/assets/books/DesertLife/introduction.html");

            }elseif($book->title == "HAPPY FAMILY") {

            $content = File::get(base_path()."/database/assets/books/HappyFamily/introduction.html");

            }else{
                $content = Str::random(800);
            }
            foreach($book_editions_ids as $book_edition_id){
                foreach($special_chapter_ids as $special_chapter_id){
                    array_push($chapters, [
                        'book_id' => $book_id,
                        'book_edition_id' => $book_edition_id,
                        'special_chapter_id' => $special_chapter_id,
                        'content' => $content,
                        'completed' => ($book->completed == 1) ? true : false,
                        'completed_at' => ($book->completed == 1) ? Carbon::now()->toDateTimeString() : null,
                        'deadline' => Carbon::now()->addDays(10),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
                }
            }
        }

        DB::table('book_special_chapters')->insert($chapters);

    }
}
