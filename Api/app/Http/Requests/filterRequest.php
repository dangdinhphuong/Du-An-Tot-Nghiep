<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TraitResponse;
use App\Rules\date_end_task;

class filterRequest extends FormRequest
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
            'date_start'          => 'nullable|date_format:Y-m-d H:i:s',
            'date_end'           =>  [
                'nullable',
                'date_format:Y-m-d H:i:s',
                'after:date_start',
            ],
            'priority'           => 'nullable|integer|min:1|max:3',
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
