<?php

namespace App\Http\Requests\Admin\SocialLinks;

use App\Models\SocialLink;
use Illuminate\Foundation\Http\FormRequest;

class DeleteSocialLink extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->user()->can('delete', SocialLink::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
