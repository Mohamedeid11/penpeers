<?php
namespace App\Repositories\Eloquent;
use App\Models\ContactMessage;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactMessageRepository extends BaseRepository implements ContactMessageRepositoryInterface{
    public function __construct(ContactMessage $model){
        $this->model = $model;
    }

    public function paginateCustomOrder(string $by, $per_page): LengthAwarePaginator
    {
        return $this->model->orderBy($by)->paginate($per_page);
    }

}
        