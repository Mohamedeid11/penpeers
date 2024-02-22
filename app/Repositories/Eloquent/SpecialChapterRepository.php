<?php
namespace App\Repositories\Eloquent;
use App\Models\SpecialChapter;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;

class SpecialChapterRepository extends BaseRepository implements SpecialChapterRepositoryInterface{
    public function __construct(SpecialChapter $model){
        $this->model = $model;
    }

}
