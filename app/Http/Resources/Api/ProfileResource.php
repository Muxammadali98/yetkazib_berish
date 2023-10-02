<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'photo' => isset($this->photo) ? asset('uploads/images/' . $this->photo) : null,
            'today_ticket_count' => $this->today_ticket_count + $this->today_assignment_count,
            'this_month_ticket_count' => $this->this_month_ticket_count + $this->this_month_assignment_count,
            'job_title' => __('api-custom.job-supplier'),
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'address' => $this->address,
            'regions' => RegionResourse::collection($this->regions)
        ];
    }
}
