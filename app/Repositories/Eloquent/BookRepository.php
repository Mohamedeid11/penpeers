<?php
namespace App\Repositories\Eloquent;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\BookSpecialChapter;
use App\Models\User;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class BookRepository extends BaseRepository implements BookRepositoryInterface{
    public function __construct(Book $model){
        $this->model = $model;
    }
    public function addSpecialChapter($data, $book_id, $edition_num): Model
    {
        $book = $this->get($book_id);
        $edition = $book->editions()->where(['edition_number'=>$edition_num])->firstOrFail();
        $book->special_chapters()->attach($data['special_chapter_id'], [
            'book_edition_id' => $edition->id,
            'deadline' => $data['deadline'] ?? null
        ]);
        $book_special_chapter = BookSpecialChapter::whereHas('book', function (Builder $query) use ($book){
            $query->where(['books.id'=>$book->id]);
        })->whereHas('book_edition', function (Builder $query) use ($edition){
            $query->where(['book_editions.id'=>$edition->id]);
        })->whereHas('special_chapter', function(Builder $query) use ($data){
            $query->where(['special_chapters.id'=>$data['special_chapter_id']]);
        })->first();
        $book->special_chapters_authors()->attach($book->lead_author->id, [
            'book_special_chapter_id' => $book_special_chapter->id,
            'book_edition_id' => $edition->id,
        ]);
        return $book_special_chapter;
    }
    public function addChapter($data, $book_id, $edition_num):bool
    {
        $book = $this->get($book_id);
        $edition = $book->editions()->where(['edition_number'=>$edition_num])->firstOrFail();
        $book_chapter = $book->book_chapters()->create([
            'name'=>$data['name'],
            'order'=>$data['order'],
            'book_edition_id' => $edition->id,
            'deadline' => $data['deadline'] ?? null
        ]);
        $book->book_chapter_authors()->attach(User::find($data['author_id'])->id, [
            'book_chapter_id' => $book_chapter->id,
            'book_edition_id' => $edition->id
        ]);
        return true;
    }

    public function CKEditorDownload($html, $book , $type , $chapter = null) : bool
    {
        $file = [
            'html' => $html->render()  ,
            'css' => view('web.partials.book-pdf-styles')->render(),
            "options" => [
                "margin_top" => "2cm",
                "margin_bottom"=> "3cm",
                "margin_left"=> "2cm",
                "margin_right"=> "2cm",
                "format"=> "A4",
                "footer_html"=> '<div style="text-align: center; font-size: 10px; margin-bottom: 10mm">
                                    <p> -<span class="pageNumber"></span>- </p>
                                    <p> PenPeers </p>
                                </div>',
            ],
        ];

        $accessKey = env('AccessKey' , 'xbuK8sxjby7KrwSBEZbbEPzcCAkdoUuqEULSc7XnnzTNIbhHvmzO9Cju0wPs');
        $environmentId = env('EnvironmentId' , 'dWZhabNDJS72Lyzziw1r');

        $payload = [
            'aud' => $environmentId,
            'iat' => time(),
        ];

        $jwt_token = JWT::encode($payload, (string) $accessKey, 'HS256');

        $response = Http::withHeaders(['Authorization' =>$jwt_token ])->post('https://pdf-converter.cke-cs.com/v1/convert', $file);
        $path = storage_path('app/public/uploads/book_pdfs/')  . $book->title ;

        if(!File::exists($path)) {
            File::makeDirectory($path,  0777, true, true);
        }
        if ($type == 'first_pages') {

            $file_name = "0.pdf";

        }elseif ($type == 'introduction') {

            $file_name = "1.pdf";

        }elseif($chapter != null && $type == 'chapter' ){

            $file_name = ( $chapter->order + 1 ) . ".pdf";

        }elseif($type == 'last_pages') {
            $last_chapter_order = $book->book_chapters()->orderBy('order', 'DESC')->first();
            $file_name = ( $last_chapter_order->order + 2 ) . ".pdf";
        }
        file_put_contents($path . '/' . $file_name, $response);

        return true;
    }
}
