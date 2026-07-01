<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantCloseRequest extends FormRequest
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
            'close_refund_amount' => 'integer|nullable',
            'close_charge' => 'integer|nullable',
            'close_date' => 'required',
            'close_reason' => 'required|max:255',
        ];
    }
}
