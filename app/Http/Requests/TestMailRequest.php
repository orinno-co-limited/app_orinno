<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestMailRequest extends FormRequest
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
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:100',
        ];
    }
}
