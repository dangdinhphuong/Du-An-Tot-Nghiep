<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
        $id = $request->route('room') ?? null;

        return [
            'name' => [
                'required',
                Rule::unique('rooms')->ignore($id ? $id : null)->where('floor_id', $request->only('floor_id'))->whereNull('deleted_at'),
            ],
            'floor_id' => [
                'required',
                'integer',
                'exists:floors,id',
            ],
            'room_type_id' => [
                'required',
                'integer',
                'exists:room_types,id',
            ],
            'numberOfBed' => 'nullable|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Mời bạn nhập tên trường',
            'name.unique' => 'Tên này đã tồn tại',
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
