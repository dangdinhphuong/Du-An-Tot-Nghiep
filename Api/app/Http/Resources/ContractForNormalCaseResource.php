<?php

namespace App\Http\Resources;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractForNormalCaseResource extends JsonResource
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
            'deleted_at' => $this->deleted_at,
            'student' => new StudentResource($this->user),
            'contract_bed' => $this->bed == null ? new BedResource(Bed::onlyTrashed()->where('id', $this->bed_id)->first())  : new BedResource($this->bed),
            'contract_room' => $this->room == null ? new RoomForNormalCaseResource(Room::onlyTrashed()->where('id', $this->room_id)->first()) : new RoomForNormalCaseResource($this->room),
        ];
    }
}
