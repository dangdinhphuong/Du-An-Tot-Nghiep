<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use  App\Rules\nameUnique;
use Illuminate\Http\Request;

class ProjectUpdateRequest extends FormRequest
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
                'unique:projects,name,' . $request->route('project')->id . ',id,deleted_at,NULL',
                new nameUnique(),
                'min:3'
            ],
            'hotline' => 'required|digits_between:10,11|numeric',
            'description' => 'required',
            'address' => 'required|min:3',
            'cycle_collect' => 'required|numeric|between:1,12',
            'extension_time' => 'required|numeric|min:1|max:31'

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => false
        ], 400));
    }

    public function attributes()
    {
        return [
            'name' => "Tên dự án",
            'hotline' => "Số điện thoại dự án",
            'description' => "Mô tả dự án",
            'address' => "Địa chỉ dự án",
        ];
    }
}
