<?php
namespace App\Repositories\Eloquent;
use App\Models\BookPublishRequest;
use App\Repositories\Interfaces\BookPublishRequestRepositoryInterface;

class BookPublishRequestRepository extends BaseRepository implements BookPublishRequestRepositoryInterface{
    public function __construct(BookPublishRequest $model){
        $this->model = $model;
    }

}
        