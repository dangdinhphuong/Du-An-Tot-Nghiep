<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\roleIdUpdateStaff;
use Illuminate\Http\Exceptions\HttpResponseException;

class StaffUpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string|between:1,100',
            'last_name' => 'required|string|between:1,100',
            'birth' => 'required|date_format:Y-m-d',
            'birth_place' => 'required|string|between:5,200',
            'gender' => 'required|integer|min:0|max:1',
            'address' => 'required|string|between:10,200',
            'phone' => 'required|digits_between:10,11|numeric',
            'status' => 'required|integer|min:0|max:1',
            
            'role_id' => [
                'required',
                'integer',
                'min:1',
                new roleIdUpdateStaff(),
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => false,
        ], 400));
    }
}
