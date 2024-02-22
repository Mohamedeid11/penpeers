<?php

namespace App\Repositories\Eloquent;

use App\Models\CorporateEmployee;
use App\Repositories\Interfaces\CorporateEmployeeRepositoryInterface;

class CorporateEmployeeRepository extends BaseRepository implements CorporateEmployeeRepositoryInterface
{
    public function __construct(CorporateEmployee $model)
    {
        $this->model = $model;
    }
}
