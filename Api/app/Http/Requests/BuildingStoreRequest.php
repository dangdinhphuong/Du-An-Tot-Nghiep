<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\ProjectExistRule;
use App\Rules\nameUniqueStoreBuiding;
class BuildingStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                // 'unique:buildings,name,NULL,id,deleted_at,NULL',
                new nameUniqueStoreBuiding(),
                'string',
                'min:3'
            ],
            'total_area' => 'required|numeric|min:1',
            'note' => 'required',
            'project_id' => [
                'required',
                'integer',
                new ProjectExistRule
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
