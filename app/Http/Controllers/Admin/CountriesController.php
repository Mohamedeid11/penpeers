<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Countries\CreateCountry;
use App\Http\Requests\Admin\Countries\DeleteCountry;
use App\Http\Requests\Admin\Countries\EditCountry;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountriesController extends Controller
{
    /**
     * CountriesController constructor.
     * Authorize requests using App\Policies\Admin\CountryPolicy.
     */
    public function __construct()
    {
        $this->authorizeResource(Country::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $countries = Country::all();
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $country = new Country;
        return view('admin.countries.create-edit', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCountry $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateCountry $request)
    {
        $data =  $request->only(['name', 'nationality', 'key']);
        $data['active'] = $request->filled('active') ? true : false;
        $path = $request->file('image')->store('uploads/countries', ['disk'=>'public']);
        $data['image'] = $path;
        $country = Country::create($data);
        if ($request->get('translations')){

            $country->add_translations($request->get('translations'));
        }
        $request->session()->flash('success', __('admin.success_add', ['thing'=>__('global.country')]) );
        return redirect(route('admin.countries.index'));
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
     * @param Country $country
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Country $country)
    {
        return view('admin.countries.create-edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditCountry $request
     * @param Country $country
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EditCountry $request, Country $country)
    {
        $data =  $request->only(['name', 'nationality', 'key']);
        $data['active'] = $request->filled('active') ? true : false;
        if ($request->hasFile('image')){
            Storage::disk('public')->delete($country->image);
            $path = $request->file('image')->store('uploads/countries', ['disk'=>'public']);
            $data['image'] = $path;
        }
        $country->update($data);
        if ($request->get('translations')){

            $country->add_translations($request->get('translations'));
        }
        $request->session()->flash('success', __('admin.success_edit', ['thing'=>__('global.country')])  );
        return redirect(route('admin.countries.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCountry $request
     * @param Country $country
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(DeleteCountry $request, Country $country)
    {
        Storage::disk('public')->delete($country->image);
        $country->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.country')]) );
        return redirect(route('admin.countries.index'));
    }

    /**
     * Batch remove specified resources from storage
     *
     * @param DeleteCountry $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteCountry $request){
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_countries = Country::whereIn('id', $ids);
        Storage::disk('public')->delete($target_countries->pluck('image'));
        $target_countries->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.country')]) );
        return redirect(route('admin.countries.index'));
    }

    /**
     *
     * @param $country_id
     * @return false|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggle_active($country_id){
        $this->authorize('viewAny', Country::class);
        $country = Country::findOrFail($country_id);
        $country->active = !$country->active;
        $country->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }
}
