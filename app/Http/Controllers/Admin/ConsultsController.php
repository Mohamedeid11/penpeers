<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Consults\DeleteConsult;
use App\Models\Consult;
use App\Services\Admin\ConsultsCrudService;
use Illuminate\Http\Request;

class ConsultsController extends Controller
{
    private $consultsCrudService;
    public function __construct(ConsultsCrudService $consultsCrudService)
    {
        $this->consultsCrudService = $consultsCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consults = $this->consultsCrudService->getAllConsults();
        return view('admin.consults.index', compact('consults'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteConsult $request, Consult $consult)
    {
        $consult->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.consult')]) );
        return redirect(route('admin.consults.index'));
    }

    public function batchDestroy(DeleteConsult $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_consults = Consult::whereIn('id', $ids);
        $target_consults->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.consults')]) );
        return redirect(route('admin.consults.index'));
    }
}
