<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TraitResponse;

class StudentInteractRequest extends FormRequest
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
            'request_type'              => 'required|integer|min:1',
            'content'          => 'required|string',
            'status'           => 'required|integer|min:1',
            'student_id' => 'required|integer|min:1',
            'staff_id' => 'required|integer|min:1',
            'check' => 'nullable|integer|min:0|max:2',
            // check là sự chọn có muốn giửi email hay ko.|| 0vs2 là giửi / 1 là không giửi
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
