<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProjectStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:projects,name,NULL,id,deleted_at,NULL|string|min:3',
            'hotline' => 'required|digits_between:10,11|numeric',
            'description' => 'required',
            'address' => 'required|min:3',
            'cycle_collect' => 'required|numeric|between:1,12',
            'extension_time' => 'required|numeric|min:1'
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
