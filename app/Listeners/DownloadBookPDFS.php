<?php

namespace App\Listeners;

use App\Events\GenerateBookPDFS;
use App\Services\GeneralWebService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DownloadBookPDFS
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
    public function handle(GenerateBookPDFS $event)
    {
        $book = $event->book;

        if ($event->type == 'covered_pages'){
            //this one for downloading first pages of the book
            $first_pages_html = view('web.pdfs.first-pdf-pages', compact('book' ));
            $last_page_html = view('web.pdfs.last-pdf-pages', compact('book' ));

            $this->generalWebService->CKEditorDownload($first_pages_html, $book , 'first_pages');
            $this->generalWebService->CKEditorDownload($last_page_html, $book , 'last_pages');

        }elseif($event->type == 'chapter'){

            $chapter = $event->chapter;
            // this one for downloading chapters
            $html = view('web.pdfs.chapter-pdf-html', compact('book' , 'chapter'));
            $this->generalWebService->CKEditorDownload($html, $book , 'chapter' , $chapter);

        }elseif($event->type == 'special_chapter'){

            $html = view('web.pdfs.special-chapter-pdf-html' , compact('book'));
            $this->generalWebService->CKEditorDownload($html,$book , 'introduction');

        }

    }
}
