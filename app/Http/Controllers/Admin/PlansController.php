<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plans\CreatePlanRequest;
use App\Http\Requests\Admin\Plans\DeletePlanRequest;
use App\Http\Requests\Admin\Plans\EditPlan;
use App\Http\Requests\Admin\Plans\EditPlanRequest;
use App\Models\Plan;
use App\Services\Admin\PlansCrudService;
use Illuminate\Http\Request;

class PlansController extends BaseAdminController
{
    private $plansCrudService;
    public function __construct(PlansCrudService $plansCrudService)
    {
        parent::__construct(Plan::class);
        $this->authorizeResource(Plan::class);
        $this->plansCrudService = $plansCrudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = $this->plansCrudService->getAllPlans();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plan = new Plan;
        return view('admin.plans.create-edit', compact('plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePlanRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePlanRequest $request)
    {
        $this->plansCrudService->createPage($request->all());
        return redirect(route('admin.plans.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.create-edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPlanRequest $request, Plan $plan)
    {
        $this->plansCrudService->updatePlan($plan, $request->all());
        return redirect(route('admin.plans.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePlanRequest $request, Plan $plan)
    {
        $this->plansCrudService->deletePlan($plan);
        return redirect(route('admin.plans.index'));
    }
    /**
     * @param DeletePage $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeletePlanRequest $request){
        $this->plansCrudService->batchDeletePlans($request->all());
        return redirect(route('admin.plans.index'));
    }
    public function toggle_active($plan_id){
        $this->authorize('viewAny', Plan::class);
        $this->plansCrudService->togglePlanActive($plan_id);
        return json_encode([
            'status' => 1,
            'message' => __("admin.status_success")
        ]);
    }
}
