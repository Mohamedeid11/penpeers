<?php
namespace App\Repositories\Eloquent;
use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface{
    public function __construct(Country $model){
        $this->model = $model;
    }

}
        