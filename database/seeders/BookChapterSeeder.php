<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookEdition;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class BookChapterSeeder extends Seeder
{
    protected $faker;
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }
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
            $book = Book::where('id' ,$book_id)->first();
            $completed_books = [
                "living_book" => [
                    [
                        'name' => "The principles",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/LivingInTheLight/The-principles.html"),
                    ],
                    [
                        'name' => "Intuition",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/LivingInTheLight/Intuition.html"),
                    ],
                    [
                        'name' => "Exploring Our Many Selves",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/LivingInTheLight/Exploring-Our-Many-Selves.html"),
                    ],
                ],
                "drawing_book" => [
                    [
                        'name' => "The Right Stuff: Materials and References",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/RealisticDrawingSecrets/chapter-1.html"),
                    ],
                    [
                        'name' => "Realistic Drawing Techniques",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/RealisticDrawingSecrets/chapter-2.html"),
                    ],
                    [
                        'name' => "Drawing Practice",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/RealisticDrawingSecrets/chapter-3.html"),
                    ],
                ],
                "dragon_war_book" => [
                    [
                        'name' => "HE SONG OF DEATH",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/warOfDragon/chapter-1.html"),
                    ],
                    [
                        'name' => "SILVANOSHEI",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/warOfDragon/chapter-2.html"),
                    ],
                    [
                        'name' => "THE HOLY FIRE",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/warOfDragon/chapter-3.html"),
                    ],
                ],
                "wild_beauty_book" => [
                    [
                        'name' => "THE TEN THOUSAND THINGS",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/TheWildBeauty/chapter-1.html"),
                    ],
                    [
                        'name' => "HE PACIFIC CREST TRAIL, VOLUME 1: CALIFORNIA",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/TheWildBeauty/chapter-2.html"),
                    ],
                    [
                        'name' => "RANGE OF LIGHT",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/TheWildBeauty/chapter-3.html"),
                    ],
                ],
                "mystery_of_universe_book" => [
                    [
                        'name' => "A BRIEF HISTORY OF A BRIEF HISTORY",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/MysteryofUniverse/chapter-1.html"),
                    ],
                    [
                        'name' => "IS THE END IN SIGHT FOR THEORETICAL PHYSICS?",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/MysteryofUniverse/chapter-2.html"),
                    ],
                    [
                        'name' => "THE QUANTUM MECHANICS OF BLACK HOLES",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/MysteryofUniverse/chapter-3.html"),
                    ],
                ],
                "memorise_book" => [
                    [
                        'name' => "A Map of the Brain",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/Memorise/chapter-1.html"),
                    ],
                    [
                        'name' => "Keeping the Brain Healthy",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/Memorise/chapter-2.html"),
                    ],
                    [
                        'name' => "Memory and the Brain",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/Memorise/chapter-3.html"),
                    ],
                ],
                "our_world_our_life_book" => [
                    [
                        'name' => "Why The Rich Don't Work For Money",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/OurWorldOurLife/chapter-1.html"),
                    ],
                    [
                        'name' => "The Man Who Could See The Future",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/OurWorldOurLife/chapter-2.html"),
                    ],
                    [
                        'name' => "What Can I Do?",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/OurWorldOurLife/chapter-3.html"),
                    ],
                ],
                "art_of_illustrator_book" => [
                    [
                        'name' => "Introduction to Adobe Illustrator CC",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/ArtOfIllustrator/chapter-1.html"),
                    ],
                    [
                        'name' => "Working with documents",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/ArtOfIllustrator/chapter-2.html"),
                    ],
                    [
                        'name' => "Creating basic shapes",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/ArtOfIllustrator/chapter-3.html"),
                    ],
                ],
                "magazine_book" => [
                    [
                        'name' => "Feature Writing Today",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/Magazine/chapter-1.html"),
                    ],
                    [
                        'name' => "Careers as Feature Writers and Editors",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/Magazine/chapter-2.html"),
                    ],
                    [
                        'name' => "Finding Good Feature Article Ideas",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/Magazine/chapter-3.html"),
                    ],
                ],
                "family_book" => [
                    [
                        'name' => "FAMILY RULES",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-1.html"),
                    ],
                    [
                        'name' => "Happy Place",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-2.html"),
                    ],
                    [
                        'name' => "Be Thankful",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-3.html"),
                    ],
                    [
                        'name' => "Enjoy The Little Things",
                        'order' => 4,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-4.html"),
                    ],
                    [
                        'name' => "Yes, You Can!",
                        'order' => 5,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-5.html"),
                    ],
                    [
                        'name' => "Family tips",
                        'order' => 6,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-6.html"),
                    ],
                    [
                        'name' => "FAMILY RULES test",
                        'order' => 7,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-1.html"),
                    ],
                    [
                        'name' => "Happy Place test",
                        'order' => 8,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-2.html"),
                    ],
                    [
                        'name' => "Be Thankful test",
                        'order' => 9,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-3.html"),
                    ],
                    [
                        'name' => "Enjoy The Little Things test",
                        'order' => 10,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-4.html"),
                    ],
                    [
                        'name' => "Yes, You Can! test",
                        'order' => 11,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-5.html"),
                    ],
                    [
                        'name' => "Family tips test",
                        'order' => 12,
                        'content' => File::get(base_path()."/database/assets/books/HappyFamily/chapter-6.html"),
                    ],
                ],
                "desert_life_book" => [
                    [
                        'name' => "نقد وتقريظ",
                        'order' => 1,
                        'content' => File::get(base_path()."/database/assets/books/DesertLife/chapter-1.html"),
                    ],
                    [
                        'name' => "الصحراء، التاريخ الذي اتخذ مظهرًا تاريخيًا",
                        'order' => 2,
                        'content' => File::get(base_path()."/database/assets/books/DesertLife/chapter-2.html"),
                    ],
                    [
                        'name' => "القناة",
                        'order' => 3,
                        'content' => File::get(base_path()."/database/assets/books/DesertLife/chapter-3.html"),
                    ],
                ],
            ];
            if ($book->title == "Living In The Light"){
                $completed_book = $completed_books['living_book'];

            }elseif($book->title == "Realistic Drawing Secrets"){

                $completed_book = $completed_books['drawing_book'];

            }elseif($book->title == "War of Dragon"){

                $completed_book = $completed_books['dragon_war_book'];

            }elseif($book->title == "The Wild Beauty"){

                $completed_book = $completed_books['wild_beauty_book'];

            }elseif($book->title == "Memorise"){

                $completed_book = $completed_books['memorise_book'];

            }elseif($book->title == "Our World Our Life"){

                $completed_book = $completed_books['our_world_our_life_book'];

            }elseif($book->title == "Art Of Illustrator"){

                $completed_book = $completed_books['art_of_illustrator_book'];

            }elseif($book->title == "Magazine"){

                $completed_book = $completed_books['magazine_book'];

            }elseif($book->title == "حياة الصحراء"){

                $completed_book = $completed_books['desert_life_book'];

            }elseif($book->title == "HAPPY FAMILY"){

                $completed_book = $completed_books['family_book'];

            }else{

                $completed_book = $completed_books['mystery_of_universe_book'];

            }
            foreach ($completed_book as $key => $one) {
                foreach ($book_editions_ids as $book_edition_id) {
                    {
                        if ( $book->title == "Our World Our Life"
                            || $book->title == "Living In The Light"
                            || $book->title == "Realistic Drawing Secrets"
                            || $book->title == "War of Dragon"
                            || $book->title == "The Wild Beauty"
                            || $book->title == "Mystery of Universe"
                            || $book->title == "Memorise"
                            || $book->title == "Art Of Illustrator"
                            || $book->title == "Magazine"
                            || $book->title == "حياة الصحراء"
                            || $book->title == "HAPPY FAMILY"
                        ) {
                            $name = $one['name'];
                            $content = $one['content'];
                            $order = $one['order'];
                        }else {
                            $name = $this->faker->name;
                            $content = Str::random(800);
                            $order = $this->faker->randomNumber();
                        }
                        array_push($chapters, [
                            'book_id' => $book_id,
                            'book_edition_id' => $book_edition_id,
                            'name' => $name,
                            'order' => $order,
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
        }

        DB::table('book_chapters')->insert($chapters);
    }
}
