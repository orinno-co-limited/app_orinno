<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRecurringRequest extends FormRequest
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
            'invoice_prefix' => 'required',
            'property_id' => 'required',
            'property_unit_id' => 'required',
            'due_day_after' => 'required',
            'recurring_type' => 'required',
            'cycle_day' => 'required_if:recurring_type,3',
            'invoiceItem.invoice_type_id.*' => 'required',
            'invoiceItem.amount.*' => 'required',
            'invoiceItem.description.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'cycle_day.required_if' => 'The cycle day field is required.',
            'invoiceItem.invoice_type_id.*.required' => 'The invoice type field is required.',
            'invoiceItem.amount.*.required' => 'The amount field is required.',
            'invoiceItem.description.*.required' => 'The description field is required.',
            'due_day_after.required' => 'This field is required.',
        ];
    }
}
