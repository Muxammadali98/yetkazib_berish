<?php

namespace App\Repositories\Api;

use App\Models\AdditionalNotice;
use App\Models\AdditionalNoticeUser;
use App\Models\User;

class NotificationRepository implements \App\Interfaces\Api\Repositories\NotificationRepositoryInterface
{
    public function getAllActive()
    {
        $user = User::select('id')
            ->with(['additionalNotices' => function($query) {
                $query->select(['additional_notices.id', 'title_' . app()->getLocale() . ' AS title', 'message_' . app()->getLocale() . ' AS message', 'image', 'type', 'additional_notices.created_at'])
                ->with(['users' => function($query) {
                    $query->select(['additional_notice_id', 'user_id', 'additional_notice_user.status'])
                        ->where('user_id', request()->user()->id);
                }]);
            }])
            ->where('id', request()->user()->id)->first();

        $readNotifications = [];
        $unreadNotifications = [];

        if (!empty($user->additionalNotices)) {
            foreach ($user->additionalNotices as $notice) {
                foreach ($notice->users as $userNotice) {
                    if ($userNotice->status == AdditionalNoticeUser::STATUS_READ) {
                        $readNotifications[] = $notice;
                    } elseif ($userNotice->status == AdditionalNoticeUser::STATUS_UNREAD) {
                        $unreadNotifications[] = $notice;
                    }
                }
            }
        }

        return [
            'read' => $readNotifications,
            'unread' => $unreadNotifications,
        ];
    }
}
