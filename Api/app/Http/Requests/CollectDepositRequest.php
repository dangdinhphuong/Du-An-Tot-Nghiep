<?php

namespace App\Http\Requests;

use App\Rules\CollectDateRule;
use App\Rules\OnlyStudentRule;
use App\Rules\PaymentTypeExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class CollectDepositRequest extends FormRequest
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
            'collection_date' => [
                'required',
                new CollectDateRule(
                    request()->route('id')
                )
            ],
            'user_id' => [
                'required',
                new OnlyStudentRule()
            ],
            'amount_of_money' => 'required|numeric',
            'payment_type' => [
                'required',
                'string',
                new PaymentTypeExistsRule()
            ],
            'note' => [
                'string',
                'between:1,255'
            ]
        ];
    }
}
