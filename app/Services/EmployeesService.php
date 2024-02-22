<?php

namespace App\Services;

use App\Mail\CorporateEmployeeInvitation;
use App\Models\CorporateEmployee;
use App\Models\User;
use App\Repositories\Interfaces\CorporateEmployeeRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Storage;

class EmployeesService
{
    private $corporateEmployeeRepository;
    public function __construct(CorporateEmployeeRepositoryInterface $corporateEmployeeRepository)
    {
        $this->corporateEmployeeRepository = $corporateEmployeeRepository;
    }

    public function addEmployee(array $data)
    {
        $user = auth()->guard('web')->user();
        if ($user->items <= $user->employees()->count()) {
            session()->flash('error', __('global.reach_end_of_plan'));
            return back();
        }
        $this->corporateEmployeeRepository->create([
            'user_id' => $user->id,
            'email' => $data['email'],
            'name' => $data['name']
        ]);
        Mail::to($data['email'])->send(new CorporateEmployeeInvitation($user, $data['email']));
        session()->flash('success', __('global.success'));
        return back();
    }


    public function addEmployeeExcel(array $data)
    {
        ////////////// Excel /////////////
        $rules = array(
            'sheet' => 'required|mimes:xlsx,csv,xls',
        );

        $validator = Validator::make($data, $rules);
        // process the form
        if ($validator->fails()) {
            session()->flash('error', __('global.error_in_file_extenison'));
            return back();
        } else {
            try {
                $tempPathFile = $data["sheet"]->store('temp');
                $realPathFile = storage_path('app') . '/' . $tempPathFile;
                $datasheets = Excel::toArray([], $realPathFile);
                $countRowsFirstSheet = count($datasheets[0]);
            } catch (\Exception $e) {
                // failed Load data
                session()->flash('error',  __('global.error_in_file_extenison'));
                return back();
            }
        }

        ////////////////Excel ///////////

        $user = auth()->guard('web')->user();
        if ($user->items <= $user->employees()->count() || $countRowsFirstSheet > ($user->items - $user->employees()->count())) {
            session()->flash('error', __('global.list_of_excel_more_than_rest_invitaiton') . $countRowsFirstSheet);
            return back();
        }


        $found_emails = 0;
        $error_message = __('global.error_in_some_emails');
        foreach ($datasheets[0] as $row) {

            $email = $row[0];
            $name = $row[1] ?: "";
            if (!$email) {
                continue;
            }

            $check_email_at_users = User::where('email', $email)->exists();
            $check_email_at_corporate_employees = CorporateEmployee::where('email', $email)->exists();

            if ($check_email_at_users || $check_email_at_corporate_employees) {
                $error_message .= "$email, ";
                $found_emails++;
                continue;
            }

            $this->corporateEmployeeRepository->create([
                'user_id' => $user->id,
                'email' => $email,
                'name'  => $name
            ]);
            Mail::to($email)->send(new CorporateEmployeeInvitation($user, $email));
        }

        $message =  "Success";
        $message .= $found_emails ? " " . $error_message : "";

        session()->flash('success', $message);
        return back();
    }

    public function deleteEmployee(CorporateEmployee $employee)
    {
        if ($employee->company->id == auth()->guard('web')->user()->id) {
            $employee->user && $employee->user->delete();
            $employee->delete();
        }
        session()->flash('success',  "Success");
    }

    public function resendEmployeeInvitation($employee)
    {
        $user = auth()->guard('web')->user();
        if ($employee->company->id == $user->id) {
            Mail::to($employee['email'])->send(new CorporateEmployeeInvitation($user, $employee['email']));
        }
        session()->flash('success', "Success");
    }



    public function arrangeEmployees($employees, User $company)
    {
        $success = 0;
        if (count($employees) > 0) {
            foreach ((array) $employees as $employee_id => $arrange) {
                $employee = User::where(['id' => $employee_id, 'company_id' => $company->id])->firstOrFail();
                $employee->arrange = $arrange;
                $employee->save();
                $success++;
            }
        }
        if ($success == count($employees)) {
            return true;
        }

        return false;
    }
}
