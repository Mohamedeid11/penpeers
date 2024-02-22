<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Occupations\CreateOccupation;
use App\Http\Requests\Admin\Occupations\DeleteOccupation;
use App\Models\Occupation;
use Illuminate\Http\Request;
use App\Services\Admin\OccupationsCrudService;

class OccupationsController extends Controller
{
    private $occupationsCrudService;

    public function __construct(OccupationsCrudService $occupationsCrudService)
    {
        $this->authorizeResource(Occupation::class);
        $this->occupationsCrudService = $occupationsCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $occupations = $this->occupationsCrudService->getAllOccupations();
        return view('admin.occupations.index', compact('occupations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $occupation = new Occupation();
        return view('admin.occupations.create-edit', compact('occupation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOccupation $request)
    {
        $this->occupationsCrudService->createOccupation($request->all());
        return redirect(route('admin.occupations.index'));
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
    public function edit(Occupation $occupation)
    {
        return view('admin.occupations.create-edit', compact('occupation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Occupation $occupation)
    {
        $this->occupationsCrudService->updateOccupation($occupation, $request->all());
        return redirect(route('admin.occupations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOccupation $request, Occupation $occupation)
    {
        $this->occupationsCrudService->deleteOccupation($occupation);
        return redirect(route('admin.occupations.index'));
    }

    public function batchDestroy(DeleteOccupation $request){
        $this->occupationsCrudService->batchDeleteOccupations($request->all());
        return redirect(route('admin.occupations.index'));
    }
}
