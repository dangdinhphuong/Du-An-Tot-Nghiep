<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
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

    public function rules(Request $request)
    {
        $id = $request->route('asset') ?? null;
        //dd($id);
        return [
            'name' => [
                'required',
                //Rule::unique('assets')->ignore($id ?? 0),
            ],
            'price' => 'required|integer|min:1000',
            'min_inventory' => 'required|integer|min:0',
            'description' => 'required',
            //'image' => 'required',
            'producer_id' => [
                'required',
                'integer',
                'exists:producers,id',
            ],
            'unit_asset_id' => [
                'required',
                'integer',
                'exists:unit_assets,id',
            ],
            'asset_type_id' => [
                'required',
                'integer',
                'exists:asset_types,id',
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mời bạn nhập tên trường',
           // 'name.unique' => 'Tên này đã tồn tại',
            'price.required' => 'Mời nhập giá',
            'price.integer' => 'Giá phải là số',
            'price.min' => 'Giá phải lớn hơn 1000',
            'min_inventory.required' => 'Mời nhập số lượng',
            'min_inventory.integer' => 'Số lượng phải là số',
            'min_inventory.min' => 'Số lượng phải lớn hơn 0',
           // 'image.required' => 'Mời chọn ảnh',
            'description.required' => 'Mời điền mô tả'
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
