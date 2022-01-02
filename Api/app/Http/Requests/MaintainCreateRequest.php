<?php

namespace App\Http\Requests;

use App\Rules\khdkRule;
use App\Rules\MaintainUpdateRule;
use App\Rules\periodRule;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class MaintainCreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|between:5,100|unique:maintenaces',
            'type' => [
                'required',
                'string',
                new khdkRule()
            ],
            'note' => 'required|string|between:5,100',
            'user_undertake_id' => ['required', 'integer', 'min:1', new MaintainUpdateRule()],
            'date_start' => 'required|date_format:Y-m-d|after:' . Carbon::now()->subDay(1),
            'date_end' => 'required|date_format:Y-m-d|after:date_start',
            'periodic' => [
                'string',
                new periodRule(),
                function ($attribute, $value, $fail) {
                    if (request()->input('type') == 'KHDK') {
                        return 'required';
                    } else {
                        return $fail('Trường ' . $attribute . ' dùng loại này không cần');
                    }
                }
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
