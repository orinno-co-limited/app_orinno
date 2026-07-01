<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
        return [
            'multiple.unit_name.*' => 'required',
            'multiple.bedroom.*' => 'required',
            'multiple.bath.*' => 'required',
            'multiple.kitchen.*' => 'required',
        ];
    }
}
