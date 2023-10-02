<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Log;
use App\Models\Telegram;

class AnswerService implements \App\Interfaces\Services\AnswerServiceInterface
{
    public function managerCancel($application_id)
    {
        Answer::where('application_id', $application_id)->update(['status' => Answer::STATUS_MANAGER_REJECTED]);
        LogService::setLog($application_id, auth()->user()->id, Log::STATUS_REJECTED);
    }
    public function contractCancel($application_id)
    {
        Answer::where('application_id', $application_id)->update(['status' => Answer::STATUS_CONTRACT_REJECTED]);
        LogService::setLog($application_id, auth()->user()->id, Log::STATUS_REJECTED);
    }

    public function managerAccepted($application_id)
    {
        Answer::where('application_id', $application_id)->update(['status' => Answer::STATUS_MANAGER_ACCEPTED]);
        LogService::setLog($application_id, auth()->user()->id, Log::STATUS_ACCEPTED);
    }
    public function contractAccepted($application_id)
    {
        Answer::where('application_id', $application_id)->update(['status' => Answer::STATUS_CONTRACT_ACCEPTED]);
        LogService::setLog($application_id, auth()->user()->id, Log::STATUS_ACCEPTED);
    }

    public function strReplace($target, $var, $text)
    {
        $text = $this->stripTags($text);
        return str_replace($target, $var, $text);
    }

    public function messagePrepare($application_id, $message)
    {
        $answer = Answer::where('application_id', $application_id)
            ->with(['client' => function($query) {
                $query->select(['id', 'full_name', 'chat_id']);
            }])
            ->first();
        return [
            'message' => $this->strReplace('[[name]]', $answer->client->full_name, $answer->language == Answer::LANGUAGE_UZ ? $message->body_uz : $message->body_ru),
            'chat_id' => $answer->client->chat_id
        ];
    }

    public function send($application_id, $message)
    {
        $telegram = new Telegram();
        $data = $this->messagePrepare($application_id, $message);
        $telegram->sendMessage($data['chat_id'], $data['message']);
    }

    public function stripTags($text): string
    {
        return strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><tg-spoiler><a><code><pre>');
    }
}
