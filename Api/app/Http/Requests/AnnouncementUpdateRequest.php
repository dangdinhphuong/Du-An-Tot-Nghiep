<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\titleUniqueAnnoucement;
class AnnouncementUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level' => 'required|integer|min:0|max:2',
            'title' => [
                'string',
                'min:0',
                new titleUniqueAnnoucement()
            ],
            'content' => 'required|string',
            'range' => 'string',
            'date_start' => 'required|date|after:now',
            'date_end' => 'required|date|after:date_start',
            'type_announce_id' => [
                'required',
                'integer',
                'exists:type_announces,id',
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
