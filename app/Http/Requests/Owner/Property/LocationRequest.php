<?php

namespace App\Http\Requests\Owner\Property;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
        $rules = [
//            'country' => 'required',
//            'city' => 'required',
//            'state' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
//            'map_link' => 'required|url',
        ];
        return $rules;
    }
}
