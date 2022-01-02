<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class ReceiptRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'collection_date' => 'required|date_format:Y-m-d',
            'user_id' => 'required|integer|min:1',
            'amount_of_money' => " required|regex:/^\d{1,13}(\.\d{1,4})?$/",
            'payment_type' => 'required|string',
            'note' => 'nullable|string',
            'receipt_reason_id' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => false
        ], 400));
    }
}
