<?php

namespace App\Http\Requests\Owner\Property;

use Illuminate\Foundation\Http\FormRequest;

class PropertyInformationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules =  [
            'property_type' => 'required|in:1,2',
            'own_property_name' => 'exclude_unless:property_type,1|required',
            'own_number_of_unit' => 'exclude_unless:property_type,1|required|numeric',
            'own_description' => 'exclude_unless:property_type,1|required',
            'lease_property_name' => 'exclude_unless:property_type,2|required',
            'lease_number_of_unit' => 'exclude_unless:property_type,2|required|numeric',
            'lease_amount' => 'exclude_unless:property_type,2|required|numeric',
            'lease_start_date' => 'exclude_unless:property_type,2|required',
            'lease_end_date' => 'exclude_unless:property_type,2|required',
            'lease_description' => 'exclude_unless:property_type,2|required',
        ];

        return $rules;
    }
}
