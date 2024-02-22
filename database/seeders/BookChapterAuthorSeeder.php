<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookChapterAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookChapters = BookChapter::all();


        foreach ($bookChapters as $chapter) {

            $lead_chapters = ["Why The Rich Don't Work For Money"
                ,"The Man Who Could See The Future"
                ,"What Can I Do?"
                ,"HE SONG OF DEATH"
                ,"SILVANOSHEI"
                ,"THE HOLY FIRE"
                ,"Introduction to Adobe Illustrator CC"
                ,"Working with documents"
                ,"Creating basic shapes"
                ,"THE TEN THOUSAND THINGS"
                ,"HE PACIFIC CREST TRAIL, VOLUME 1: CALIFORNIA"
                ,"RANGE OF LIGHT"
                ,"The principles"
                ,"Intuition"
                ,"Exploring Our Many Selves"
                ,"نقد وتقريظ"
                ,"الصحراء، التاريخ الذي اتخذ مظهرًا تاريخيًا"
                ,"القناة"
                ,"The Right Stuff: Materials and References"
                ,"Realistic Drawing Techniques"
                ,"Drawing Practice"
                ,"Careers as Feature Writers and Editors"
            ];
            if ( in_array($chapter->name ,$lead_chapters)){
                $user_id = $chapter->book->lead_author->id;

            }elseif($chapter->name === "A Map of the Brain" || $chapter->name === "Keeping the Brain Healthy" ) {

                $user_id = User::where('username' , 'islam')->first()->id;

            }elseif($chapter->name === "Memory and the Brain"
                ||( $chapter->name === "A BRIEF HISTORY OF A BRIEF HISTORY"
                || $chapter->name === "Finding Good Feature Article Ideas") )
            {
                $user_id = User::where('username' , 'nahla')->first()->id;

            }elseif($chapter->name === "IS THE END IN SIGHT FOR THEORETICAL PHYSICS?"
                ||( $chapter->name === "THE QUANTUM MECHANICS OF BLACK HOLES"
                || $chapter->name === "Feature Writing Today") ){

                $user_id = User::where('username' , 'mohamed')->first()->id;

            }else{

                $user_id = BookParticipant::where(['book_id' => $chapter->book_id , 'status' => 1])->inRandomOrder()->first()->user_id;
            }
            DB::table('book_chapter_authors')->insert([
                'book_id' => $chapter->book_id,
                'book_edition_id' => $chapter->book_edition_id,
                'user_id' => $user_id,
                'book_chapter_id' => $chapter->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        // //add Co-auhtors
        // $bookChapters = BookChapter::orderBy('id', 'desc')->take(5)->get();
        // foreach ($bookChapters as $chapter) {
        //     DB::table('book_chapter_authors')->insert([
        //         'book_id' => $chapter->book_id,
        //         'book_edition_id' => $chapter->book_edition_id,
        //         'user_id' => BookParticipant::where(['book_id' => $chapter->book_id, 'status' => 1, 'book_role_id' => 2])->inRandomOrder()->first()->user_id,
        //         'book_chapter_id' => $chapter->id,
        //         'created_at' => Carbon::now()->toDateTimeString(),
        //         'updated_at' => Carbon::now()->toDateTimeString()
        //     ]);
        // }
    }
}
