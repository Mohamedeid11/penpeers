<?php
namespace App\Repositories\Eloquent;
use App\Models\Interest;
use App\Repositories\Interfaces\InterestRepositoryInterface;

class InterestRepository extends BaseRepository implements InterestRepositoryInterface{
    public function __construct(Interest $model){
        $this->model = $model;
    }

}
        