<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\TraitResponse;

class activeUserRequest extends FormRequest
{
    use TraitResponse;
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
            'status'           => 'required|integer|min:0|max:1',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->responseApi($validator->errors(), 400));
    }
}
