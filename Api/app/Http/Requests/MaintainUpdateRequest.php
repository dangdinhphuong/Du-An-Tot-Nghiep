<?php

namespace App\Http\Requests;

use App\Rules\MaintainUpdateRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MaintainUpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => [
                'required',
                'string',
                'between:5,100',
                'unique:maintenaces,name,' . $request->route('id') . ',id'
            ],
            'note' => 'required|string|between:5,100',
            'user_undertake_id' => ['required', 'integer', 'min:1', new MaintainUpdateRule],
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
