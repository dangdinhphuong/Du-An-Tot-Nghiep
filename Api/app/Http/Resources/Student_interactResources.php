<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Student_interactResources extends JsonResource
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
            "id"=> $this->id,
            "request_type"=> $this->request_type,
            "content"=> $this->content,
            "status"=> $this->status,
            "check"=> $this->check,
            "date_send"=> $this->date_send,
            // "student_id"=>$this->student_id,
            // "staff_id"=> $this->staff_id,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            'student' => new Student_staffResources($this->student),
            'staff' => new Student_staffResources($this->staff)
        ];
    }
}
