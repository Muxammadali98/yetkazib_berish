<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SupplierNotificationController extends Controller
{
    public function __construct(
        private $title,
        private $body,
        private $to,
        private $id,
    )
    {
    }

    public function send()
    {
        $fcmApiUrl = 'https://fcm.googleapis.com/fcm/send';

        $payload = [
            'to' => $this->to,
            'priority' => 'high',
            'topic' => 'test',
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => $this->id,
                'status' => 'done'
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => config('app.notification_key'),
            'Content-Type' => 'application/json',
        ])->post($fcmApiUrl, $payload);

        return $response->json();
    }
}
