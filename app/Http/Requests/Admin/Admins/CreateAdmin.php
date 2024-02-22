<?php

namespace App\Http\Requests\Admin\Admins;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class CreateAdmin extends FormRequest
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
//        $admin = new Admin;
//        return array_merge($admin->rules, [
//            'name' => 'required',
//            'username' => 'required|unique:admins',
//            'role_id' => 'required|exists:roles,id',
//            'email' => 'required|email|unique:admins',
//            'password' => 'required|min:8'
//        ]);

        return [
            'name' => 'required',
            'username' => 'required|unique:admins',
//            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8'
        ];
    }
}
