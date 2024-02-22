<?php
namespace App\Services;

use App\Repositories\Interfaces\SocialLinkRepositoryInterface;
use Illuminate\Support\Str;

class BaseService {

    private $socialLinkRepository;
    public function __construct(SocialLinkRepositoryInterface $socialLinkRepository){
        $this->socialLinkRepository = $socialLinkRepository;
    }
    
    public function getSocialLinks(){
        return $this->socialLinkRepository->all();
    }
}
