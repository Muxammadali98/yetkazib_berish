<?php

namespace App\Interfaces\Api\Services;

interface PhoneVerificationServiceInterface
{
    public function send($recipient, $text);
}
