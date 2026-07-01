<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TicketRequest extends FormRequest
{
    use ResponseTrait;
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
            'title' => 'required|max:255',
            'details' => 'required',
            'topic_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => 'The topic field is required.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->header('accept') == "application/json") {
            $error = '';
            if ($validator->fails()) {
                $error = $validator->errors()->first();
            }
            return $this->validationErrorApi($validator, $error);
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
