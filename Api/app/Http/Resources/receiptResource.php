<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class receiptResource extends JsonResource
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
            'contract_id' => $this->contract_id,
            'invoice_id' => $this->invoice_id,
            'collection_date' => $this->collection_date,
            'user_id' => $this->user_id,
            'amount_of_money' => $this->amount_of_money,
            'payment_type' => $this->payment_type,
            'note' => $this->note,
            'id' => $this->id,
            'receipt_reason_id' => $this->receipt_reason_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'student' => new StudentResource($this->users)
        ];
    }

}
