<?php
namespace App\Repositories\Eloquent;
use App\Models\Occupation;
use App\Repositories\Interfaces\OccupationRepositoryInterface;

class OccupationRepository extends BaseRepository implements OccupationRepositoryInterface{
    public function __construct(Occupation $model){
        $this->model = $model;
    }

}
        