<?php
namespace App\Repositories\Eloquent;
use App\Models\Guide;
use App\Repositories\Interfaces\GuideRepositoryInterface;

class GuideRepository extends BaseRepository implements GuideRepositoryInterface{
    public function __construct(Guide $model){
        $this->model = $model;
    }

}
        