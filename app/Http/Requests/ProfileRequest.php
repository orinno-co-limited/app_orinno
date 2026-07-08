<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileRequest extends FormRequest
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
        $authUser = auth()->user();
        $authId = $authUser->id;

        $contactNumberRule = Rule::unique('users', 'contact_number')->ignore($authId);
        if (in_array($authUser->role, [USER_ROLE_OWNER, USER_ROLE_ADMIN])) {
            $contactNumberRule = $contactNumberRule->where(fn ($query) => $query->whereIn('role', [USER_ROLE_OWNER, USER_ROLE_ADMIN]));
        } else {
            $contactNumberRule = $contactNumberRule->where(fn ($query) => $query->where('owner_user_id', getOwnerUserId()));
        }

        return [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $authId,
            'contact_number' => ['bail', 'numeric', $contactNumberRule],
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
