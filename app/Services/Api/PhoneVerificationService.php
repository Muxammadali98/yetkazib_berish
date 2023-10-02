<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class PhoneVerificationService implements \App\Interfaces\Api\Services\PhoneVerificationServiceInterface
{
    public function send($recipient, $text)
    {
        $url = "https://send.smsxabar.uz/broker-api/send";

        $auth = config('app.play_mobile_token');

        $data = [
            'messages' => [
                [
                    'recipient' => $recipient,
                    'message-id' => '2',
                    'sms' => [
                        'originator' => '3700',
                        'content' => [
                            'text' => $text
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
            'Accept' => 'application/json',
        ])->post($url, $data);

        return $response->successful();
    }
}
