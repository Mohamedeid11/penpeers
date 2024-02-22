<?php
namespace App\Repositories\Eloquent;
use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;

class PageRepository extends BaseRepository implements PageRepositoryInterface{
    public function __construct(Page $model){
        $this->model = $model;
    }

}
        