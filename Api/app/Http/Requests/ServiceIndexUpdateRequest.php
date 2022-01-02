<?php

namespace App\Http\Requests;

use App\Rules\IndexElectricGtOldRule;
use App\Rules\IndexWaterGtOldRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\RoomIdExitsRule;
use App\Rules\ServiceIndexStatusRule;
use App\Traits\TraitResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ServiceIndexUpdateRequest extends FormRequest
{
    use TraitResponse;
    /**
     * 
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
            'index_water' => [
                'required',
                'numeric',
                'gte:0',
                new IndexWaterGtOldRule($request->route('id'))
            ],
            'index_electric' => [
                'required',
                'numeric',
                'gte:0',
                new IndexElectricGtOldRule($request->route('id'))
            ],
            'room_id' => [new RoomIdExitsRule()],
            'note' => 'string|between:5,100',
            'img' => 'string|between:5,100',
            'status' => [new ServiceIndexStatusRule()]
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
