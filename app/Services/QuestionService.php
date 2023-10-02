<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionService implements \App\Interfaces\Services\QuestionServiceInterface
{

    public function createQuestion($request): bool
    {
        DB::beginTransaction();
        try {
            $question = [
                'title_uz' => $this->stripTags($request['title_uz']),
                'title_ru' => $this->stripTags($request['title_ru']),
                'pdf_title_uz' => $request['pdf_title_uz'],
                'pdf_title_ru' => $request['pdf_title_ru'],
                'type' => $request['type'],
            ];
            $buttons = [];
            if ($request['type'] == Question::TYPE_WITH_A_BUTTON) {
                foreach ($request['text_uz'] as $key => $value) {
                    $buttons[] = [
                        'text_uz' => $value,
                        'text_ru' => $request['text_ru'][$key],
                    ];
                }
            }
            $question = Question::create($question);

            if ($question->type == Question::TYPE_WITH_A_BUTTON) {
                $question->buttons()->createMany($buttons);
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function updateQuestion($request, $model): bool
    {
        DB::beginTransaction();
        try {
            $question = [
                'title_uz' => $this->stripTags($request['title_uz']),
                'title_ru' => $this->stripTags($request['title_ru']),
                'pdf_title_uz' => $request['pdf_title_uz'],
                'pdf_title_ru' => $request['pdf_title_ru'],
                'type' => $request['type'],
            ];
            $buttons = [];
            if ($request['type'] == Question::TYPE_WITH_A_BUTTON) {
                foreach ($request['text_uz'] as $key => $value) {
                    $buttons[] = [
                        'text_uz' => $value,
                        'text_ru' => $request['text_ru'][$key],
                    ];
                }
            }
            $model->update($question);

            if ($model->type == Question::TYPE_WITH_A_BUTTON) {
                $model->buttons()->delete();
                $model->buttons()->createMany($buttons);
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function stripTags($text): string
    {
        $text = $this->strReplace($text);
        return strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><tg-spoiler><a><code><pre>');
    }

    public function updateStep($list)
    {
        foreach ($list as $item) {
            Question::find($item['value'])->update(['step' => $item['order']]);
        }
    }

    public function updateStatus($id)
    {
        $question = Question::find($id);
        $question->status = $question->status == Question::STATUS_ACTIVE ? Question::STATUS_INACTIVE : Question::STATUS_ACTIVE;
        $question->save();
    }

    public function strReplace($text): string
    {
        return str_replace(['&nbsp;', '<br />'], [' ', PHP_EOL], $text);
    }
}
