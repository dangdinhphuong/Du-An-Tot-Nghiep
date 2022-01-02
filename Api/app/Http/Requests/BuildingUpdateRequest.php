<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use  App\Rules\nameUniqueUpdateBuiding;
use Illuminate\Http\Request;


class BuildingUpdateRequest extends FormRequest
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
                // 'unique:buildings,name,' . $request->route('building')->id . ',id,deleted_at,NULL',
                new nameUniqueUpdateBuiding(),
                'min:3'
            ],
            'total_area' => 'required|numeric|min:1',
            'note' => 'required',
            'project_id' => [
                'integer',
                'exists:projects,id',
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
