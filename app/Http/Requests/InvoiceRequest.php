<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class InvoiceRequest extends FormRequest
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
            'name' => 'required',
            'property_id' => 'required',
            'property_unit_id' => 'required',
            'due_date' => 'required',
            'month' => 'required',
            'invoiceItem.invoice_type_id.*' => 'required',
            'invoiceItem.amount.*' => 'required',
            'invoiceItem.description.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'invoiceItem.invoice_type_id.*.required' => 'The invoice type field is required.',
            'invoiceItem.amount.*.required' => 'The amount field is required.',
            'invoiceItem.description.*.required' => 'The description field is required.',
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
