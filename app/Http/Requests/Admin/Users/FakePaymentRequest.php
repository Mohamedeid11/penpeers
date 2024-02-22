<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FakePaymentRequest extends FormRequest
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
            'plan_id' => Rule::in(Plan::where(['account_Type'=>$this->user->account_type])->pluck('id')),
            'payment_type' => 'required',
            'value' => 'required'
        ];
    }
}
