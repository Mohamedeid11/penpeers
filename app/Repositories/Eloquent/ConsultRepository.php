<?php
namespace App\Repositories\Eloquent;
use App\Models\Consult;
use App\Repositories\Interfaces\ConsultRepositoryInterface;

class ConsultRepository extends BaseRepository implements ConsultRepositoryInterface{
    public function __construct(Consult $model){
        $this->model = $model;
    }

}
        