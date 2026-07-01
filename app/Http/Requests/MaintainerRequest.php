<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MaintainerRequest extends FormRequest
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
        $userId = isset($this->user_id) ? $this->user_id : null;
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,' . $userId,
            'contact_number' => 'bail|required|numeric|unique:users,contact_number,' . $userId,
            'password' => (is_null($userId)) ? 'required|min:6' : 'nullable',
            'property_id' => 'required',
        ];
        return $rules;
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
