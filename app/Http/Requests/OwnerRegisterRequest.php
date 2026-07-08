<?php

namespace App\Http\Requests;

use App\Rules\ReCaptcha;
use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OwnerRegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'contact_number' => [
                'required',
                'string',
                Rule::unique('users', 'contact_number')
                    ->where(fn ($query) => $query->whereIn('role', [USER_ROLE_OWNER, USER_ROLE_ADMIN])),
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'g-recaptcha-response' => [new ReCaptcha]
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response' => 'Please check',
            'terms.required' => 'You must agree to the Terms & Conditions to continue.',
            'terms.accepted' => 'You must agree to the Terms & Conditions to continue.'
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
