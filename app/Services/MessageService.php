<?php

namespace App\Services;

use App\Models\Message;

class MessageService implements \App\Interfaces\Services\MessageServiceInterface
{
    public function createMessage($request)
    {
        return Message::create([
            'body_uz' => $this->clean($request['body_uz']),
            'body_ru' => $this->clean($request['body_ru']),
            'title' => $this->clean($request['title']),
            'type' => $this->clean($request['type']),
        ]);
    }

    public function stripTags($text): string
    {
        return strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><tg-spoiler><a><code><pre>');
    }

    public function updateMessage($request, $model): bool
    {
        return $model->update([
            'body_uz' => $this->clean($request['body_uz']),
            'body_ru' => $this->clean($request['body_ru']),
            'title' => $this->clean($request['title']),
            'type' => $this->clean($request['type']),
        ]);
    }

    public function editStatus($model): bool
    {
        $this->inActiveStatus($model->type);
        return $model->update([
            'status' => !$model->status,
        ]);
    }

    public function inActiveStatus($type)
    {
        Message::where([['status', '=', Message::STATUS_ACTIVE], ['type', '=', $type]])->update(['status' => Message::STATUS_INACTIVE]);
    }

    public function clean($text): string
    {
        return str_replace(['&nbsp;', '<br />'], [' ', PHP_EOL], $text);
    }
}
