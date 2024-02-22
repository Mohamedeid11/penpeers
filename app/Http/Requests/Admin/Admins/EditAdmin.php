<?php

namespace App\Http\Requests\Admin\Admins;

use Illuminate\Foundation\Http\FormRequest;

class EditAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $admin = $this->admin;
//        return array_merge($admin->rules, [
//            'name' => 'required',
//            'username' => 'required|unique:admins,username,'.$admin->id,
//            'role_id' => 'required|exists:roles,id',
//            'email' => 'required|email|unique:admins,email,'.$admin->id,
//            'password' => 'nullable|min:8'
//        ]);
        return [
            'name' => 'required',
            'username' => 'required|unique:admins,username,'.$admin->id,
//            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'password' => 'nullable|min:8'
        ];
    }
}
