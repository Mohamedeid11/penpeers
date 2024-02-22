<?php

namespace App\Http\Requests\Web\Account;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditDetailsRequest extends FormRequest
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
        $user = User::find(auth()->guard('web')->id());

        return [
            'country_id' => 'required|exists:countries,id',
            'username' => 'required|unique:users,username,'.$user->id,
            'name' => 'required',
            'image' => 'nullable|image',
            'mobile_number' => 'numeric',
            'interests.*' => 'exists:interests,id',
            'social_links.twitter' => 'nullable|url|regex:/http(?:s)?:\/\/(?:www\.)?twitter\.com\/([a-zA-Z0-9_]+)/',
            'social_links.facebook' => 'nullable|url|regex:/http(?:s):\/\/(?:www\.)facebook\.com\/.+/i',
            'social_links.linkedin' => 'nullable|url|regex:/http(?:s)?:\/\/(?:www\.)?linkedin\.com\/([a-zA-Z0-9_]+)/',
            'social_links.youtube' => 'nullable|url|regex:/http(?:s)?:\/\/(?:www\.)?youtube\.com\/([a-zA-Z0-9_]+)/',
            'social_links.instagram' => 'nullable|url|regex:/http(?:s):\/\/(?:www\.)instagram\.com\/.+/i',
        ];
    }
}
