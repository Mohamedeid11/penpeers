<?php
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;


if (! function_exists("menu_active")){
    function menu_active($routes){
        foreach($routes as $route){
            try {
                if (Request::fullUrl() != route($route) && Request::route()->getName() == $route) {
                    return true;
                }
            }
            catch (RouteNotFoundException $e){
                if (Request::fullUrl() != route(str_replace('*', 'index', $route)) && Route::is($route)){
                    return true;
                }
            }
        }
        return false;
    }
}
if (! function_exists("dashboard_menu_active")){
    function dashboard_menu_active($routes){
        foreach($routes as $route){
            if ( Request::route()->getName() == $route || Route::is($route)) {
                return true;
            }
        }
        return false;
    }
}

//if (! function_exists("set_user_timezone")){
//    function set_user_timezone(){
//        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
//            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
//            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
//        }
//        $client  = @$_SERVER['HTTP_CLIENT_IP'];
//        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//        $remote  = @$_SERVER['REMOTE_ADDR'];
//
//        if(filter_var($client, FILTER_VALIDATE_IP))
//        {
//            $ip = $client;
//        }
//        elseif(filter_var($forward, FILTER_VALIDATE_IP))
//        {
//            $ip = $forward;
//        }
//        else
//        {
//            $ip = $remote;
//        }
//
//        $ip_info = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
//        if(!isset($ip_info->geoplugin_timezone)){
//            $ip_info->geoplugin_timezone = "Asia/Bahrain";
//        }
//
//        return $ip_info->geoplugin_timezone;
//    }
//}

if (! function_exists("bookNavActive")) {
    function bookNavActive($route_names)
    {
        return in_array(Request::route()->getName(), $route_names)? 'active' : '';
    }
}
if (! function_exists("paginateArray")) {
    function paginateArray($items, $perPage = 8, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
    }
}

if (! function_exists("dashboardPageTitle")) {
    function dashboardPageTitle()
    {
        if(in_array(Request::route()->getName(), ['web.dashboard.books.authors.index','web.dashboard.books.requests'])) {

            return  "This is where you can invite co-authors to this book, view all this book active and non-active co-authors or participation requests .";

        } else if(in_array(Request::route()->getName(), ['web.dashboard.books.editions.special_chapters.index'])) {

            return  "This is where you can view or edit your book introduction .";

        } else if(in_array(Request::route()->getName(), ['web.dashboard.books.editions.chapters.index', 'web.dashboard.books.editions.chapters.edit'])) {

            return  "This is where you can manage your chapters, creating, assigning to co-authors, editing or deleting .";

        } else if(in_array(Request::route()->getName(), ['web.dashboard.books.editions.edition_settings'])) {

            return  "This is where you can manage your book actions, editing, completing, showing, hiding or deleting .";

        } else {

            return  "This is where you manage your books , as a main author or a co-author .";

        }
    }
}
if (! function_exists("languages_list")){
    function languages_list(){
        $all_langs = LaravelLocalization::getSupportedLanguagesKeys();
        $default_lang = config('app.fallback_locale');
        $all_langs = array_filter($all_langs, function ($val) use ($default_lang) {return $val != $default_lang;});
        return $all_langs;
    }

}

if (! function_exists('ldir')){
    function ldir(){
        return LaravelLocalization::getCurrentLocaleDirection();
    }
}
if (! function_exists('locales')){
    function locales(){
        return LaravelLocalization::getSupportedLocales();
    }
}
if (! function_exists('lroute')){
    function lroute($locale){
        return LaravelLocalization::getLocalizedURL($locale);
    }
}
if (! function_exists('locale')) {
    function locale()
    {
        return LaravelLocalization::getCurrentLocale();
    }
}
if (!function_exists('ordinal')){
    function ordinal($num){
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);
        return $numberFormatter->format($num);
    }
}
if (!function_exists("inp_value")){
    function inp_value($obj, $name){
        return old($name) ? old($name) : ($obj ? $obj->$name : '');
    }
}
if (!function_exists("select_value")){
    function select_value($obj, $name, $value){
        return old($name) ? (old($name) == $value ? 'selected' : '') : ($obj ? ($obj->$name == $value ? 'selected' : '') : '');
    }
}
if (!function_exists("radio_value")){
    function radio_value($obj, $name, $value){
        return old($name) ? (old($name) == 'true' ? 'checked' : '') : ($obj ? ($obj->$name == $value ? 'checked' : '') : '');
    }
}
if(!function_exists("crop")){
    function crop($file, $details_json){
        $cropping = json_decode($details_json, true);
        if((is_array($cropping))){
            $x = array_key_exists('x',$cropping) ?  (int)$cropping['x'] : 0;
            $y =  array_key_exists('y',$cropping) ? (int)$cropping['y'] : 0;
            $w = array_key_exists('width',$cropping) ? (int)$cropping['width'] : 0;
            $h =  array_key_exists('height',$cropping) ? (int)$cropping['height']: 0;
            $img = Image::make(public_path('storage/'.$file));
            $crop_path = public_path('storage/'.$file);
            $img->crop($w, $h, $x, $y);
            $img->save($crop_path);
        }
    }
}
if(!function_exists("storage_asset")){
    function storage_asset($file, $secure=null){
        return asset('storage/'.$file, $secure);
    }
}
