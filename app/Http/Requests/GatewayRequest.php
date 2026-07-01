<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GatewayRequest extends FormRequest
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
        if($this->get('status') == ACTIVE){
            $rules = [
                'bank.name.0' => 'required_if:slug,bank|max:255',
                'bank.name.*' => 'required_if:slug,bank|max:255',
                'bank.account_name.*' => 'required_if:slug,bank|max:255',
                'bank.account_number.*' => 'required_if:slug,bank|max:255',
                'bank.status.*' => 'required_if:slug,bank',
                'key' => 'required_unless:slug,bank,cash',
                'url' => 'required_if:slug,flutterwave',
                'secret' => 'required_unless:slug,bank,cash,mollie,paystack,stripe,coinbase,paddle,xendit',
                'currency.*' => 'required',
                'conversion_rate.*' => 'required',
            ];
        }
        else{
            $rules = [];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required_if' => 'This field is required.',
            'required_unless' => 'This field is required.'
        ];
    }
}
