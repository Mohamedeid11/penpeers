<?php
namespace App\Services\Admin;

use App\Models\Guide;
use App\Repositories\Eloquent\GuideRepository;
use App\Repositories\Interfaces\GuideRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class GuidesCrudService {
    private $guideRepository;
    public function __construct(GuideRepositoryInterface $guideRepository){
        $this->guideRepository = $guideRepository;
    }
    public function getAllGuides()
    {
       return $this->guideRepository->paginate(100);
    }

    public function createGuide(array $data)
    {
        $data =  Arr::only($data, ['name', 'explanation']);
        $this->guideRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.guide')]) );
    }

    public function updateGuide(Guide $guide, array $data)
    {
        $data =  Arr::only($data, ['name', 'explanation']);
        $guide->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.guide')])  );
    }

    public function deleteGuide(Guide $guide)
    {
        $guide->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.guide')]) );
    }

    public function batchDeleteGuides(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_Guides = $this->guideRepository->getMany($ids);
        $this->guideRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.guide')]) );
    }
}
