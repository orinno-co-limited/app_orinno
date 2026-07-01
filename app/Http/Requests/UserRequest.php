<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
{
    protected $id;
    public function __construct(Request $request)
    {
        $this->id =  $request->route()->user;
    }

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
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required',
            'contact_number' => 'required|numeric|unique:users,contact_number',
        ];

        if ($this->getMethod() == 'PUT') {
            $rules['email'] = 'required|email|unique:users,email,' . $this->id;
            $rules['contact_number'] = 'bail|numeric|unique:users,contact_number,'.$this->id;
            $rules['password'] =  'nullable|min:6';
        }

        return $rules;
    }
}
