<?php
namespace App\Services\Admin;

use App\Models\Consult;
use App\Repositories\Interfaces\ConsultRepositoryInterface;
use Illuminate\Support\Arr;

class ConsultsCrudService {
    private $consultRepository;
    public function __construct(ConsultRepositoryInterface $consultRepository){
        $this->consultRepository = $consultRepository;
    }
    public function getAllConsults()
    {
       return $this->consultRepository->paginate(100);
    }

    public function deleteConsult(Consult $consult)
    {
        $consult->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.consult')]) );
    }

    public function batchDeleteConsults(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_consults = $this->consultRepository->getMany($ids);
        $this->consultRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.consults')]) );
    }
}
