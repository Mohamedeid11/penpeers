<?php

namespace App\Http\Requests\Web\Profile;

use Illuminate\Foundation\Http\FormRequest;

class SaveRecordRequest extends FormRequest
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
     * @bodyParam record_type_id int the record_type.id
     * @bodyParam text_representation string the text value
     * @bodyParam record_data string the audio as bas664 data
     * @return array
     */
    public function rules()
    {
        $record = $this->user()->records()->where(['record_type_id'=>$this->record_type_id])->first();
        $rules =  [
            'record_type_id' => 'int|required|exists:record_types,id',
            'text_representation' => 'required|min:3',
        ];
        if ($record) {
            $rules['record_data'] = 'nullable';
        }
        else {
            $rules['record_data'] = 'required';
        }
        return $rules;
    }
    public function bodyParameters(){
        return [
            'record_type_id' => [
                'description' => "the record_type.id"
            ],
            'text_representation' => [
                'description' => "the text value"
            ],
            'record_data' => [
                'description' => "the audio as bas664 data"
            ],
        ];
    }
}
