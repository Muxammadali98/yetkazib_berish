<?php

namespace App\Http\Resources\Api;

use App\Models\AdditionalNoticeUser;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public static $wrap = 'data';

    public function toArray($request)
    {
        $read = AdditionalNoticeResource::collection(
            $this->resource['read']
        );
        $unread = AdditionalNoticeResource::collection(
            $this->resource['unread']
        );

        return [
            'read' => $read,
            'unread' => $unread,
        ];
    }
}
