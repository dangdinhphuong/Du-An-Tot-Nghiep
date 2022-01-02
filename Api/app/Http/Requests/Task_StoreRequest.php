<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\date_end_task;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TraitResponse;

class Task_StoreRequest extends FormRequest
{
    use TraitResponse;
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
    public function rules()
    {
        return [
            'title'              => 'required|string|between:4,200',
            'date_start'          => 'required|date_format:Y-m-d H:i:s',
            'date_end'           =>  [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:date_start',
                new date_end_task(),
            ],
            'status'           => 'required|integer|min:1|max:4',
            'priority'           => 'required|integer|min:1|max:4',
            'desc'               => 'required|string',
            'user_undertake_id' => 'required|integer|min:1',
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
