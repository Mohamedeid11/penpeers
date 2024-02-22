<?php

namespace App\Console\Commands;

use App\Events\TracingEmail;
use App\Mail\EmailAuthorsBeforeDeadline;
use App\Models\BookChapter;
use App\Models\BookSpecialChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RemindAuthorsOfChapterDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Chapter Co-authors and Lead author of the deadline';

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
        $chapters = BookChapter::get()->where('remaining_days_before_deadline', 1);
        if(count($chapters) > 0){
            foreach ($chapters as $chapter) {
                $days_diff = $chapter->remaining_days_before_deadline;
                $all_authors = $chapter->authors;
                $all_authors = $all_authors->push($chapter->lead_author);
                foreach($all_authors as $author){
                    Mail::to($author->email)->send(new EmailAuthorsBeforeDeadline($chapter->book->title, $chapter->name, $author->name, $days_diff));
                    event(new TracingEmail($chapter->lead_author->id , $author->id , $chapter->book->id, "Chapter Deadline Reminder"));

                }
            }
        }

        // $special_chapters = BookSpecialChapter::get()->where('remaining_days_before_deadline', 1);
        // if(count($special_chapters) > 0){
        //     foreach ($special_chapters as $special_chapter) {
        //         $days_diff = $special_chapter->remaining_days_before_deadline;
        //         $all_authors = $special_chapter->authors;
        //         $all_authors = $all_authors->push($special_chapter->lead_author);
        //         foreach($all_authors as $author){
        //             Mail::to($author->email)->send(new EmailAuthorsBeforeDeadline($special_chapter->book->title, $special_chapter->special_chapter->trans('display_name'), $author->name, $days_diff));
        //         }
        //     }
        // }

        $this->info('Email sent Successfully');
    }
}
