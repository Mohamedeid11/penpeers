<?php
namespace App\Repositories\Eloquent;
use App\Models\BookEdition;
use App\Repositories\Interfaces\BookEditionRepositoryInterface;

class BookEditionRepository extends BaseRepository implements BookEditionRepositoryInterface{
    public function __construct(BookEdition $model){
        $this->model = $model;
    }

}
        