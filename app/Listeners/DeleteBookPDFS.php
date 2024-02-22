<?php

namespace App\Listeners;

use App\Events\DeleteBookFiles;
use App\Services\GeneralWebService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;

class DeleteBookPDFS
{
    private $generalWebService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GeneralWebService $generalWebService)
    {
        $this->generalWebService = $generalWebService;

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\GenerateBookPDFS  $event
     * @return void
     */
    public function handle(DeleteBookFiles $event)
    {
        $book = $event->book;

        if ($event->type == 'covered_pages'){

            $first_pdf_path = storage_path('app/public/uploads/book_pdfs/')  . $book->title .   "/0.pdf" ;
            if(File::exists($first_pdf_path)) {
                File::delete($first_pdf_path);
            }

            $last_chapter_order = $book->book_chapters()->orderBy('order', 'DESC')->first();

            $last_pdf_path = storage_path('app/public/uploads/book_pdfs/')  . $book->title . '/' . ( $last_chapter_order->order + 2 ) .  ".pdf" ;
            if(File::exists($last_pdf_path)) {
                File::delete($last_pdf_path);
            }

        }elseif($event->type == 'chapter'){
            $chapter = $event->chapter;

            $path = storage_path('app/public/uploads/book_pdfs/')  . $book->title . '/'. ( $chapter->order + 1 ). ".pdf" ;

            if(File::exists($path)) {
                File::delete($path);
            }

        }elseif($event->type == 'special_chapter'){

            $path = storage_path('app/public/uploads/book_pdfs/')  . $book->title . '/1.pdf' ;

            if(File::exists($path)) {
                File::delete($path);
            }


        }
    }
}
