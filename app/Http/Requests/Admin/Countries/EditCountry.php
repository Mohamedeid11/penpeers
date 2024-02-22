<?php

namespace App\Http\Requests\Admin\Countries;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class EditCountry extends FormRequest
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
//        $country = $this->country;
//        return array_merge($country->rules, [
//            'name'=> 'required',
//            'nationality'=> 'required',
//            'key'=> 'required',
//            'image'=> 'nullable|image',
//        ]);

        return  [
            'name'=> 'required',
            'nationality'=> 'required',
            'key'=> 'required',
            'image'=> 'nullable|image',
        ];
    }
}
