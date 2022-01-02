<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProducerRequest extends FormRequest
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
        $id = $request->route('producer') ?? null;
        
        return [
            'name' => [
                'required',
                Rule::unique('producers')->ignore($id ?? 0),
            ],
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
