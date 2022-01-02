<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnourcementResource extends JsonResource
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
            'level' => $this->level,
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'type_announce_id' => $this->type_announce_id,
            'range' => $this->range,
            'typeAnnounce' => $this->typeAnnounce,
            'user'=> $this->user
        ];
    }
}
