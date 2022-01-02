<?php

namespace App\Http\Requests;

use App\Rules\CCCDRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentInfoRequest extends FormRequest
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
            'student_code' => 'required|string|between:0,100|unique:student_infos',
            'department' => 'required|string|between:0,100',
            'student_year' => 'required|integer|min:1|max:6',
            'nation' => 'string|between:0,200',
            'religion' => 'string|between:0,200',
            'CCCD' => [
                'required',
                'numeric',
                new CCCDRule(),
                'unique:student_infos'
            ],
            'date_range' => 'required|date_format:Y-m-d',
            'issued_by' => 'required|string|between:0,200',
            'student_object' => 'required|integer|min:1|max:5',
            'school' => 'required|string|between:0,200'
        ];
    }
}
