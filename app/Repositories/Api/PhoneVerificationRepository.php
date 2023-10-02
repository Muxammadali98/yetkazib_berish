<?php

namespace App\Repositories\Api;


use App\Models\User;

class PhoneVerificationRepository implements \App\Interfaces\Api\Repositories\PhoneVerificationRepositoryInterface
{

    public function getVerificationCode()
    {
        $user = User::select(['verification_code'])->where('id', auth()->user()->id)->first();
        return $user?->verification_code;
    }
}
