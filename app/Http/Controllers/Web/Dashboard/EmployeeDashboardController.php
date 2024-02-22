<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Web\BaseWebController;
use App\Models\CorporateEmployee;
use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Requests\Web\Dashboard\Employees\EmployeeAddRequest;
use App\Services\EmployeesService;

class EmployeeDashboardController extends BaseWebController
{
    private $employeesService;
    public function __construct(EmployeesService $employeesService)
    {
        parent::__construct();
        $this->employeesService = $employeesService;
        $this->middleware('check_plan_validity')->except(['employees' , 'PublicEmployeesArrange' , 'sign' , 'arrangeEmployees']);

    }

    public function employees()
    {
        /** @var User $user */
        $user = auth()->guard('web')->user();
        $user->account_type === 'corporate' || abort(404);
        $invitations = $user->employees;
        $employees = $user->employees->where('employee_id', '!=', 0)->sortBy('arrange');

        return view('web.dashboard.employees.index', compact('user', 'employees', 'invitations'));
    }

    public function PublicEmployeesArrange()
    {
        /** @var User $user */
        $user = auth()->guard('web')->user();
        $user->account_type === 'corporate' || abort(404);
        $employees = $user->employees->where('employee_id', '!=', 0)->where('public', 1)->sortBy('arrange');

        return view('web.account.corporate.employees-public-arrange', compact('user', 'employees'));
    }
    public function addEmployee(EmployeeAddRequest $request)
    {
        return $this->employeesService->addEmployee($request->all());
    }

    public function addEmployeeExcel(Request $request)
    {

        return $this->employeesService->addEmployeeExcel($request->all());
    }

    public function sign(Request $request, $company, $email)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        return $company . " " . $email;
    }
    public function deleteEmployee(CorporateEmployee $employee)
    {
        $this->employeesService->deleteEmployee($employee);
        return redirect(route('web.dashboard.employees.list'));
    }
    public function resendEmployeeInvitation(CorporateEmployee $employee)
    {
        $this->employeesService->resendEmployeeInvitation($employee);
        return redirect(route('web.dashboard.employees.list'));
    }


    public function arrangeEmployees(Request $request)
    {
        $employees = $request->input("ids");
        $user = auth()->guard('web')->user();
        if ($user->account_type != 'corporate') {
            abort(404);
        }
        $arrange = $this->employeesService->arrangeEmployees($employees, $user);
        if ($arrange) {
            return response()->json([
                'status' => 'success',
                'message' => __('global.arranged_successfully')
            ]);
        }
    }
}
