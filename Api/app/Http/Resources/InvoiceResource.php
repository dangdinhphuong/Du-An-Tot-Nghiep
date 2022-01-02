<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'status' => $this->status == config('Months.status.chua') ? 'Chưa tạo hóa đơn' : 'Đã tạo hóa đơn',
            'room' => $this->room,
            // 'room' => new RoomForNormalCaseResource($this->room)
            'created_at' => $this->created_at
        ];
    }
}
