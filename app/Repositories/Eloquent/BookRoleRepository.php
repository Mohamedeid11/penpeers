<?php
namespace App\Repositories\Eloquent;
use App\Models\BookRole;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;

class BookRoleRepository extends BaseRepository implements BookRoleRepositoryInterface{
    public function __construct(BookRole $model){
        $this->model = $model;
    }

}
        