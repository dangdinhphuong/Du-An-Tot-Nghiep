<?php

namespace App\Http\Requests;

use App\Rules\ReceiptReasonIdUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class ReceiptReasonDeleteRequest extends FormRequest
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
            'id' => [
                new ReceiptReasonIdUniqueRule()
            ]
        ];
    }
}
