<?php
namespace App\Repositories\Eloquent;
use App\Models\BookParticipant;
use App\Repositories\Interfaces\BookParticipantRepositoryInterface;

class BookParticipantRepository extends BaseRepository implements BookParticipantRepositoryInterface{
    public function __construct(BookParticipant $model){
        $this->model = $model;
    }
}
        