<?php

namespace App\Http\Requests;

use App\Rules\BedExistInRoomRule;
use App\Rules\BedExistsContractRule;
use App\Rules\EndDayNotSurpassCycleDayRule;
use App\Rules\OnlyPermamentServiceRule;
use App\Rules\OnlyStudentRule;
use App\Rules\ProjectServiceDiffProjectRule;
use App\Rules\ProjectServiceRule;
use App\Rules\RoomIdExitsRule;
use App\Rules\ServiceHasTheSameProjectRule;
use App\Rules\StudentHasContractRule;
use App\Rules\UserExistsRule;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ContractStoreRequest extends FormRequest
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
            'start_day' => 'required|date_format:Y-m-d|after:' . Carbon::now()->subDay(1),
            'end_day' => [
                'required',
                'date_format:Y-m-d',
                new EndDayNotSurpassCycleDayRule(request(['room_id', 'start_day']))
            ],
            'price' => 'required|integer|min:1000',
            'deposit' => 'required|integer|min:1000',
            'note' => 'between:0,100',
            'room_id' => [
                'required',
                'integer',
                'between:1,10000000',
                new RoomIdExitsRule()
            ],
            'bed_id' => [
                'required',
                'integer',
                'between:1,10000000',
                new BedExistInRoomRule(request('room_id')),
                new BedExistsContractRule()
            ],
            'user_id' => [
                'required',
                'integer',
                'between:1,10000000',
                new OnlyStudentRule(),
                new UserExistsRule(),
                new StudentHasContractRule()
            ],
            'project_service_id.*' => [
                'integer',
                new ProjectServiceRule(),
                new ServiceHasTheSameProjectRule(request('project_service_id')),
                new ProjectServiceDiffProjectRule($request->only('room_id')),
                new OnlyPermamentServiceRule()
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
