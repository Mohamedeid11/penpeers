<?php
namespace App\Services\Admin;

use App\Repositories\Interfaces\PlanRepositoryInterface;
use Illuminate\Support\Arr;

class PlansCrudService
{
    private $planRepository;
    public function __construct(PlanRepositoryInterface $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function getAllPlans()
    {
        return $this->planRepository->paginate(100);
    }

    public function createPage(array $data)
    {
        $data = Arr::only($data, ['account_type', 'period', 'active', 'price', 'items', 'name']);
        $data['active'] = Arr::has($data, 'active') && $data['active'];
        $this->planRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.plan')]) );
    }

    public function updatePlan($plan, array $data)
    {
        $data = Arr::only($data, ['account_type', 'period', 'active', 'price', 'items', 'name']);
        $data['active'] = Arr::has($data, 'active') && $data['active'];
        $plan->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.plan')]) );
    }

    public function deletePlan($plan)
    {
        $plan->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.plan')]) );
    }

    public function batchDeletePlans(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $wrong_role = $this->planRepository->all(true)->whereIn('id', $ids)->where(['is_system'=>true])->first();
        if($wrong_role){
            session()->flash('error', __('admin.no_delete_sys_plans') );
        }
        else {
            $this->planRepository->deleteMany($ids);
            session()->flash('success', __('admin.success_delete', ['thing' => __('global.plans')]));
        }
    }

    public function togglePlanActive($plan_id)
    {
        $plan = $this->planRepository->get($plan_id);
        $plan ? $plan->update(['active'=>!$plan->active]) : null;
    }
}
