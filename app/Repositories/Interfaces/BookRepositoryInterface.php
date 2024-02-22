<?php
namespace App\Repositories\Interfaces;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

interface BookRepositoryInterface{
    public function addSpecialChapter($data, $book_id, $edition_num): Model;
    public function addChapter($data, $book_id, $edition_num):bool;
    public function CKEditorDownload($html, $book , $type , $chapter = null):bool;
}
