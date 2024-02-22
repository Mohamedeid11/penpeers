<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Services\BaseService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BaseWebController extends Controller
{
    public function __construct()
    {
        $this->baseService = App::make(BaseService::class);
        $social_links = $this->baseService->getSocialLinks();
        $genres = Genre::all();
        View::share(['social_links' => $social_links, 'genres' => $genres]);
    }
}
