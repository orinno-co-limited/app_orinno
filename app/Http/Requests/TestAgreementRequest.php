<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestAgreementRequest extends FormRequest
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
            'file' => 'required|file|mimes:doc,docx,pdf|max:2048',
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|max:255'
        ];
    }
}
