<?php
namespace App\Repositories\Eloquent;
use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface{
    public function __construct(Blog $model){
        $this->model = $model;
    }

}
        