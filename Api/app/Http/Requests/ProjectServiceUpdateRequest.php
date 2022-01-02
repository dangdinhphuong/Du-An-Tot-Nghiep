<?php

namespace App\Http\Requests;

use App\Rules\nameMustFitWithUnitRule;
use App\Rules\ProjectExistRule;
use App\Rules\ProjectIdMustFitRule;
use App\Rules\ProjectServiceRule;
use App\Rules\UniqueNameForServiceRule;
use App\Rules\UnitExistRule;
use App\Traits\TraitResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProjectServiceUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            '*.id' => ['required', new ProjectServiceRule()],
            '*.name' => [
                'required',
                'string',
                'between:5,100',
                // new UniqueNameForServiceRule(request()->all())
                // 'unique:project_services,name'
            ],
            '*.unit_price' => 'required|numeric',
            '*.unit' => ['required', new UnitExistRule(), new nameMustFitWithUnitRule(request()->all())],
            '*.project_id' => ['required', 'integer', 'min:1', new ProjectExistRule(), new ProjectIdMustFitRule($request->all())]
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
