<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' =>  $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'gender' => $this->gender ==  config('User.gender.male') ? 'Nam' : 'Nữ',
            'status' => $this->status == config('User.action.activated') ? 'Được kích hoạt' : 'Bị vô hiệu hóa',
            'contract' => $this->when(request('load_contract'), $this->contract)
        ];
    }
}
