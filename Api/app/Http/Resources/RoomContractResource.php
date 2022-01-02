<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomContractResource extends JsonResource
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
            'stat_day' => $this->start_day,
            'end_day' => $this->end_day,
            'price' => $this->price,
            'deposit' => $this->deposit,
            'note' => $this->note,
            'deposit_state' => $this->deposit_state == config('contract.deposit_state.chua_thu') ? 'Chưa thu' : 'Đã thu',
            'student' => new StudentResource($this->user)
        ];
    }
}
