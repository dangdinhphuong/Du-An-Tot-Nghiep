<?php

namespace App\Http\Requests;

use App\Traits\TraitResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CCCDRule;
use App\Rules\requestId;
use Illuminate\Http\Request;
use App\Models\StudentInfo;

class studentUpdatefrom extends FormRequest
{
    use TraitResponse;



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
        $Info = StudentInfo::where('user_id', $request->requestId)->first();

        if ($Info == null) {
            throw new HttpResponseException($this->responseApi("", 400, "Yêu cầu không chính xác"));
        }
        return [
            'first_name' => 'required|string|between:5,100',
            'last_name' => 'required|string|between:5,100',
            'birth' => 'required|date_format:Y-m-d',
            'birth_place' => 'required|string|between:5,200',
            'gender' => 'required|integer|min:0|max:1',
            'address' => 'required|string|between:10,200',
            'phone' => 'required|min:10|numeric',
            'status'           => 'required|integer|min:0|max:1',
            'student_code' => 'required|string|between:4,100|unique:student_infos,student_code,' . $Info->id,
            'department' => 'required|string|between:5,100',
            'student_year' => 'required|integer|min:1|max:6',
            'nation' => 'required|string|between:4,200',
            'religion' => 'required|string|between:5,200',
            'CCCD' => [
                'required',
                'nullable',
                'numeric',
                new CCCDRule(),
                'unique:student_infos,CCCD, ' . $Info->id,
            ],
            'date_range' => 'required|date_format:Y-m-d',
            'issued_by' => 'required|string|between:5,200',
            'student_object' => 'required|integer|min:1|max:5',
            'school' => 'required|string|between:10,200',
            'farther_name' => 'required|string|between:2,100',
            'mother_name' => 'required|string|between:2,100',
            'address_relative' => 'required|string|between:2,100',
            'phone_relative' => 'required|digits_between:10,11|numeric'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->responseApi($validator->errors(), 400));
    }
}
