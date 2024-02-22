<?php
namespace App\Repositories\Eloquent;
use App\Models\NewsletterEmail;
use App\Repositories\Interfaces\NewsletterEmailRepositoryInterface;

class NewsletterEmailRepository extends BaseRepository implements NewsletterEmailRepositoryInterface{
    public function __construct(NewsletterEmail $model){
        $this->model = $model;
    }

}
        