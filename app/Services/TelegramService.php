<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Application;
use App\Models\Step;
use Illuminate\Support\Facades\DB;
use PDF;

class TelegramService implements \App\Interfaces\Services\TelegramServiceInterface
{
    public function setRegionToStepTable($question, $region_id, $chat_id)
    {
        $step = Step::where('chat_id', $chat_id)->first();
        $step->region_id = $region_id;
        $step->question_type = $question?->type;
        $step->save();
    }

    public function setQuestionToTempTable($questions, $client_id)
    {
        $this->removeTempTable($client_id);
        if ($questions) {
            foreach ($questions as $question) {
                $temp = new \App\Models\Temp();
                $temp->client_id = $client_id;
                $temp->question_id = $question->id;
                $temp->save();
            }
        }
    }

    public function removeTempTable($client_id)
    {
        \App\Models\Temp::where('client_id', $client_id)->delete();
    }

    public function setApplication($client_id)
    {
        $model = Application::where([['client_id', '=', $client_id], ['status', '=', Application::STATUS_INACTIVE]])->first();
        if (!$model) {
            $application = new Application();
            $application->client_id = $client_id;
            $application->save();
        }
    }

    public function editTempStatus($id)
    {
        $temp = \App\Models\Temp::find($id);
        $temp->status = \App\Models\Temp::STATUS_ACTIVE;
        $temp->save();
    }

    public function setAnswer($question_id, $region_id, $client_id, $answer, $language)
    {
        $application = Application::where([['client_id', '=', $client_id], ['status', '=', Application::STATUS_INACTIVE]])->first();
        Answer::create([
            'application_id' => $application->id,
            'question_id' => $question_id,
            'region_id' => $region_id,
            'text' => $answer,
            'language' => $language,
            'client_id' => $client_id,
        ]);
    }

    public function removeAnswerCache($client_id)
    {
        Answer::where([['status', '=', Answer::STATUS_INACTIVE],['client_id', '=', $client_id]])->delete();
    }

    public function setQuestionTypeToStepTable($type, $chat_id)
    {
        $step = Step::where('chat_id', $chat_id)->first();
        $step->question_type = $type ?? null;
        $step->save();
    }

    public function answersAcceptance($client_id)
    {
        DB::transaction(function () use ($client_id) {
            $application = Application::where([['client_id', '=', $client_id], ['status', '=', Application::STATUS_INACTIVE]])->first();
            $application->status = Application::STATUS_ACTIVE;
            $application->save();
            Answer::where([['status', '=', Answer::STATUS_INACTIVE],['client_id', '=', $client_id]])->update(['status' => Answer::STATUS_ACTIVE]);
        });
    }

    public function closeApplication($client_id)
    {
        DB::transaction(function () use ($client_id) {
            $this->removeAnswerCache($client_id);
            $this->removeTempTable($client_id);
            $step = Step::where('client_id', $client_id)->first();
            $step->question_type = null;
            $step->save();
        });
    }

    public function setFullName($client_id, $full_name)
    {
        $client = \App\Models\Client::find($client_id);
        $client->full_name = $full_name;
        $client->save();
    }

    public function setPhoneNumber($client_id, $phone_number)
    {
        $client = \App\Models\Client::find($client_id);
        $client->phone_number = $phone_number;
        $client->save();
    }

    public function setActiveClient($client_id)
    {
        $client = \App\Models\Client::find($client_id);
        $client->status = \App\Models\Client::STATUS_ACTIVE;
        $client->save();
    }

    public function stripTags($text): string
    {
        $text = str_replace(['&nbsp;', '<br />'], [' ', PHP_EOL], $text);
        return strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><tg-spoiler><a><code><pre>');
    }
}
