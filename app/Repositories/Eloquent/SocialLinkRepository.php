<?php
namespace App\Repositories\Eloquent;
use App\Models\SocialLink;
use App\Repositories\Interfaces\SocialLinkRepositoryInterface;

class SocialLinkRepository extends BaseRepository implements SocialLinkRepositoryInterface{
    public function __construct(SocialLink $model){
        $this->model = $model;
    }

}
        