<?php

namespace App\Http\Requests;

use App\Rules\nameMustFitWithUnitRule;
use App\Rules\ProjectExistRule;
use App\Rules\UniqueNameForServiceRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\UnitExistRule;
use App\Traits\TraitResponse;
use Illuminate\Foundation\Http\FormRequest;

class ProjectServiceRequest extends FormRequest
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
            '*.name' => [
                'required',
                'string',
                'between:5,100',
                new UniqueNameForServiceRule(request()->all())
            ],
            '*.unit_price' => 'required|numeric',
            '*.unit' => ['required', new UnitExistRule(), new nameMustFitWithUnitRule(request()->all())],
            '*.project_id' => ['required', 'integer', 'min:1', new ProjectExistRule()]
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
