<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $wrap = 'SupplierFile';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => asset('uploads/images/' . $this->file),
        ];
    }
}
