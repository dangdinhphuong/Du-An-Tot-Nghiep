<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRelativeRequest extends FormRequest
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
            'fathername' => 'required|string|between:2,100',
            'mothername' => 'required|string|between:2,100',
            'address_relative' => 'required|string|between:2,100',
            'phone_relative' => 'required|digits_between:10,11|numeric'
        ];
    }
}
