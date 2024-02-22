<?php

namespace Database\Seeders;

use App\Models\BookParticipant;
use App\Models\BookSpecialChapter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSpecialChapterAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookSpecialChapters = BookSpecialChapter::orderBy('id', 'asc')->get();
        foreach ($bookSpecialChapters as $chapter) {
            DB::table('book_special_chapter_authors')->insert([
                'book_id' => $chapter->book_id,
                'book_edition_id' => $chapter->book_edition_id,
                'user_id' => $chapter->book->lead_author->id,
                'book_special_chapter_id' => $chapter->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        // $bookSpecialChapters = BookSpecialChapter::orderBy('id', 'desc')->take(5)->get();
        // // add co-auhtor
        // foreach ($bookSpecialChapters as $chapter) {
        //     DB::table('book_special_chapter_authors')->insert([
        //         'book_id' => $chapter->book_id,
        //         'book_edition_id' => $chapter->book_edition_id,
        //         'user_id' => BookParticipant::where(['book_id' => $chapter->book_id, 'status' => 1, 'book_role_id' => 2])->inRandomOrder()->first()->user_id,
        //         'book_special_chapter_id' => $chapter->id,
        //         'created_at' => Carbon::now()->toDateTimeString(),
        //         'updated_at' => Carbon::now()->toDateTimeString()
        //     ]);
        // }
    }
}
