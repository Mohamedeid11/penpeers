<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genres\CreateGenre;
use App\Http\Requests\Admin\Genres\DeleteGenre;
use App\Http\Requests\Admin\Genres\EditGenre;
use Illuminate\Http\Request;
use App\Models\Genre;

class GenresController extends Controller
{
    /**
     * GenresController constructor.
     * Authorize requests using App\Policies\Admin\GenrePolicy.
     */
    public function __construct()
    {
        $this->authorizeResource(Genre::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genres = Genre::paginate(100);
        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genre = new Genre();
        return view('admin.genres.create-edit', compact('genre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateGenre  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGenre $request)
    {
        $data = $request->only(['name']);
        $genre = Genre::create($data);
        $request->session()->flash('success', __('admin.success_add', ['thing'=>__('global.genre')]) );
        return redirect(route('admin.genres.index'));
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
    public function edit(Genre $genre)
    {
        return view('admin.genres.create-edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditGenre $request, Genre $genre)
    {
        $data =  $request->only(['name']);
        $genre->update($data);
        $request->session()->flash('success', __('admin.success_edit', ['thing'=>__('global.genre')])  );
        return redirect(route('admin.genres.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre, DeleteGenre $request)
    {
        $genre->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.genre')]) );
        return redirect(route('admin.genres.index'));
    }

     /**
     * Batch remove specified resources from storage
     *
     * @param DeleteGenre $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteGenre $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_genres = Genre::whereIn('id', $ids);
        $target_genres->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.genre')]) );
        return redirect(route('admin.genres.index'));
    }
}
