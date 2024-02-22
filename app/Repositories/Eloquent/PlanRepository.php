<?php

namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Repositories\Interfaces\PlanRepositoryInterface;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    public function __construct(Plan $model)
    {
        $this->model = $model;
    }
}
