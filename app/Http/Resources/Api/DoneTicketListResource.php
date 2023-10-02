<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DoneTicketListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $wrap = 'Ticket';

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'contract_id' => $this->contract_id,
            'client_name' => $this->client_name,
            'address' => $this->address,
            'phone' => $this->phone,
            'additional_phone' => $this->additional_phone,
            'supplier_action' => new SupplierActionResource($this->supplierAction),
            'comment' => $this->comment ?? '',
            'created_at' => date('d.m.Y', strtotime($this->created_at)),
            'products' => ProductsListResource::collection($this->products),
            'car' => $this->car
        ];
    }
}
