<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use  App\Rules\nameFloorUnique;
use Illuminate\Http\Request;

class FloorUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'name' => [
                'required',
                'string',
                new nameFloorUnique(),
                'min:3'
            ],
            'total_area' => 'required|numeric|min:0',
            'building_id' => [
                'integer',
                'exists:buildings,id',
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
