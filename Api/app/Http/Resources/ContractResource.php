<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'name' => $this->name,
            'room_type' => new RoomTypeResource($this->room_type),
            'floor' => request('not_load_floor') ? null : new FloorContractResource($this->floor),
            'beds' => $this->when(request('load_beds'), BedResource::collection($this->beds)),
        ];
    }
}
