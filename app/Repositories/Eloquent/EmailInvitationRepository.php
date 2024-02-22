<?php
namespace App\Repositories\Eloquent;
use App\Models\EmailInvitation;
use App\Repositories\Interfaces\EmailInvitationRepositoryInterface;

class EmailInvitationRepository extends BaseRepository implements EmailInvitationRepositoryInterface{
    public function __construct(EmailInvitation $model){
        $this->model = $model;
    }

}
        