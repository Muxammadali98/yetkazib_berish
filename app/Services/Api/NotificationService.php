<?php

namespace App\Services\Api;

use App\Models\AdditionalNoticeUser;

class NotificationService implements \App\Interfaces\Api\Services\NotificationServiceInterface
{

    public function markAsRead()
    {
        $additional_notice_user = AdditionalNoticeUser::where('user_id', request()->user()->id)
            ->where('additional_notice_id', request()->id)
            ->first();
        if ($additional_notice_user) {
            $additional_notice_user->status = AdditionalNoticeUser::STATUS_READ;
            $additional_notice_user->save();
        }
    }
}
