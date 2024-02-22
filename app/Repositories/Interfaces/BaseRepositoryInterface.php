<?php
namespace App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface{
    public function all($lazy=false) : object;
    public function paginate($per_page) : LengthAwarePaginator;
    public function get($id) : Model;
    public function getMany($ids) : Collection;
    public function getManyBy($by, $value): Collection;
    public function getBy($by, $value): ?Model;
    public function create($data) : Model;
    public function firstOrCreate($check, $data) : Model;
    public function update($data) : Model;
    public function delete($id) : bool;
    public function deleteMany($ids) : bool;
}
