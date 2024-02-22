<?php

namespace App\Http\Requests\Admin\Plans;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
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
        return [
            'name' => 'required',
            'account_type' => 'required|in:personal,corporate',
            'period' => 'required|in:annually,biennially,triennially,quadrennially',
            'price' => 'required|numeric',
            'items' => 'required|numeric',
        ];
    }
}
