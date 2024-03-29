<?php

namespace App\Http\Requests\Admin\Roles;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $role = Auth::guard('admin')->user()->role()->where(['name'=>'admin'])->where(['is_system'=>true])->where(['guard'=>'admin'])->first();
        return $role ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $role = $this->role;

//        return array_merge($role->rules, [
//            'name' => ['required', Rule::unique('roles')
//                ->where('guard', $this->guard)->ignore($role)],
//            'guard' => ['required',Rule::unique('roles')
//                ->where('name', $this->name)->ignore($role)],
//            'display_name' => 'required',
//        ]);

        return [
            'name' => ['required', Rule::unique('roles')
                ->where('guard', $this->guard)->ignore($role)],
            'guard' => ['required',Rule::unique('roles')
                ->where('name', $this->name)->ignore($role)],
            'display_name' => 'required',
        ];
    }
}
