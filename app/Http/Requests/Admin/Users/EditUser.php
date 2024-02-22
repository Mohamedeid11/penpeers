<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class EditUser extends FormRequest
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
        $user = $this->user;
//        return array_merge($user->rules,[
//            //
//        ]);

        return [
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
            'gender' => 'required|in:0,1',
        ];
    }
}
