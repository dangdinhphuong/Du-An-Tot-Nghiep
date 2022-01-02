<?php

namespace App\Http\Requests;

use App\Rules\ProjectExistRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Room_typeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $id = $request->route('room_type') ?? null;
        return [
            'name' => [
                'required',
                Rule::unique('room_types')->ignore($id ? $id : null)->where('project_id', $request->only('project_id'))->whereNull('deleted_at'),
            ],
            'price' => 'required|integer|min:1000',
            'price_deposit' => 'required|integer|min:1000',
            'number_bed' => 'required|integer|min:1',
            'project_id' => [
                'required',
                new ProjectExistRule()
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mời bạn nhập tên trường',
            'name.unique' => 'Tên này đã tồn tại',
            'price.required' => 'Mời nhập số tiền',
            'price.integer' => 'Số tiền phải là số',
            'price.min' => 'Số tiền phải lớn hơn 1000',

            'price_deposit.required' => 'Mời nhập tiền cọc',
            'price_deposit.integer' => 'Tiền cọc phải là số',
            'price_deposit.min' => 'Tiền cọc phải lớn hơn 1000',

            'number_bed.required' => 'Mời nhập số giường',
            'number_bed.integer' => 'Số giường phải là số',
            'number_bed.min' => 'Số giường phải lớn hơn hoặc bằng 1',

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
