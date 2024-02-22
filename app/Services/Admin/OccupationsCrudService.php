<?php
namespace App\Services\Admin;

use App\Models\Occupation;
use App\Repositories\Eloquent\OccupationRepository;
use App\Repositories\Interfaces\OccupationRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class OccupationsCrudService {
    private $occupationRepository;
    public function __construct(OccupationRepositoryInterface $occupationRepository){
        $this->occupationRepository = $occupationRepository;
    }
    public function getAllOccupations()
    {
       return $this->occupationRepository->paginate(100);
    }

    public function createOccupation(array $data)
    {
        $data =  Arr::only($data, ['name']);
        $this->occupationRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.occupation')]) );
    }

    public function updateOccupation(Occupation $occupation, array $data)
    {
        $data =  Arr::only($data, ['name']);
        $occupation->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.occupation')])  );
    }

    public function deleteOccupation(Occupation $occupation)
    {
        $occupation->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.occupation')]) );
    }

    public function batchDeleteOccupations(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_occupations = $this->occupationRepository->getMany($ids);
        $this->occupationRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.occupations')]) );
    }
}
