<?php
namespace App\Repositories\Eloquent;
use App\Models\Team;
use App\Repositories\Interfaces\TeamRepositoryInterface;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface{
    public function __construct(Team $model){
        $this->model = $model;
    }

}
        