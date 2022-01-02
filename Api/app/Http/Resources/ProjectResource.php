<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'hotline' => $this->hotline,
            'description' => $this->description,
            'address' => $this->address,
            'cycle_collect' => $this->cycle_collect,
            'extension_time' => $this->extension_time,
            'buildings' => BuildingResource::collection($this->buildings)
        ];
    }
}
