<?php

namespace App\Http\Requests;

use App\Rules\PhoneMustStartWithZeroRUle;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TraitResponse;

class updateProfileRequest extends FormRequest
{
    use TraitResponse;
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
            "first_name" => "required|string",
            "last_name" => "required|string",
            "address" => "required|string",
            "phone" => [
                'required',
                'numeric',
                'digits_between:10,11',
                new PhoneMustStartWithZeroRUle()
            ]
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
