<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Bed_historiesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $id = $request->route('bed_history') ?? null;

        return [
            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],
            'bed_id' => [
                'required',
                'integer',
                'exists:beds,id',
            ],
            'day_transfer' => 'required|date_format:Y-m-d H:i:s'
        ];
    }

    public function messages()
    {
        return [
            'room_id.required' => 'Mời bạn nhập tên trường',
            'bed_id.required' => 'Mời bạn nhập tên trường',
            'day_transfer.required' => 'Mời bạn nhập thời gian',
            'day_transfer.date_format' => 'Thời gian phải dạng Y-m-d H:i:s',
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
