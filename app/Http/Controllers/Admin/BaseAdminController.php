<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BaseAdminController extends Controller
{
    private $model;
    public function __construct($model){
        $this->model = $model;
    }
    public function translate(Request $request, $obj_id){
        if (property_exists($this->model, 'trans_fields')) {
            $obj = app()->make($this->model)->find($obj_id);
            $this->authorize('translate', $obj);
            return view('admin.translations.translate', compact('obj'));
        }
        else {
            abort(404);
        }
    }
    public function translation(Request $request, $obj_id){
        if (property_exists($this->model, 'trans_fields')) {
            $obj = app()->make($this->model)->find($obj_id);
            $this->authorize('translate', $obj);
            $obj->add_translations([$request->language => $request->only($obj->trans_fields)]);
            return back();
        }
        else {
            abort(404);
        }
    }
}
