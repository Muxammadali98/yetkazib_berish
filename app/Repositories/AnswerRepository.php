<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Message;
use App\Models\Question;
use App\Models\Telegram;
use Illuminate\Support\Facades\DB;

class AnswerRepository implements \App\Interfaces\Repositories\AnswerRepositoryInterface
{
    public function getNewAnswers($status)
    {
        $model = Answer::orderBy('created_at', 'desc')
            ->with(['question' => function ($query) {
                $query->select('id', 'pdf_title_uz', 'pdf_title_ru', 'type');
            }, 'client' => function ($query) {
                $query->select('id', 'full_name', 'phone_number');
            }])
            ->whereIn('id', function ($query) use ($status) {
                $query->select(DB::raw('max(id)'))
                    ->from('answers')
                    ->where('status', $status)
                    ->groupBy('application_id');
            });

        if (auth()->user()->hasRole('manager')) {
            $model = $model
                ->whereIn('region_id', function ($query) {
                    $query->select('region_id')
                        ->from('user_region')
                        ->where('user_id', auth()->id());
                });
        }
        return $model->paginate(Answer::PAGE_SIZE);
    }
    public function getAnswerByApplication($application_id)
    {
        return Answer::where('application_id', $application_id)
            ->with(['question' => function ($query) {
                $query->select('id', 'pdf_title_uz', 'pdf_title_ru', 'type');
            }, 'client' => function ($query) {
                $query->select('id', 'full_name', 'phone_number');
            }, 'region' => function ($query) {
                $query->select('id', 'name_uz', 'name_ru');
            }])
            ->get();
    }

    public function getAnswerImages($application_id)
    {
        $telegram = new Telegram();
        $answers = $this->getAnswerByApplication($application_id);
        $telegram_url = Telegram::SERVER_URL . '/file/bot' . Telegram::TOKEN;
        $image_hash = [];
        $image_src = [];
        foreach ($answers as $answer) {
            if ($answer->question->type == Question::TYPE_PHOTO) {
                $image_hash[] = $answer->text;
            }
        }
        if ($image_hash) {
            foreach ($image_hash as $item) {
                $file_path = $telegram->getFile($item);
                if ($file_path) {
                    $image_src[] = $telegram_url . '/' . $file_path->result->file_path;
                }

            }
        }
        return $image_src;
    }

    public function getAcceptedAnswers($status)
    {
        $model = Answer::orderBy('created_at', 'desc')
            ->with(['question' => function ($query) {
                $query->select('id', 'pdf_title_uz', 'pdf_title_ru', 'type');
            }, 'client' => function ($query) {
                $query->select('id', 'full_name', 'phone_number');
            }])
            ->whereIn('id', function ($query) use ($status) {
                $query->select(DB::raw('max(id)'))
                    ->from('answers')
                    ->where('status', $status)
                    ->groupBy('application_id');
            });

        if (auth()->user()->hasRole('manager')) {
            $model = $model
                ->whereIn('region_id', function ($query) {
                    $query->select('region_id')
                        ->from('user_region')
                        ->where('user_id', auth()->id());
                });
        }
        return $model->paginate(Answer::PAGE_SIZE);
    }

    public function getRejectedAnswers($status)
    {
        $model = Answer::orderBy('created_at', 'desc')
            ->with(['question' => function ($query) {
                $query->select('id', 'pdf_title_uz', 'pdf_title_ru', 'type');
            }, 'client' => function ($query) {
                $query->select('id', 'full_name', 'phone_number');
            }])
            ->whereIn('id', function ($query) use ($status) {
                $query->select(DB::raw('max(id)'))
                    ->from('answers')
                    ->where('status', $status)
                    ->groupBy('application_id');
            });

        if (auth()->user()->hasRole('manager')) {
            $model = $model
                ->whereIn('region_id', function ($query) {
                    $query->select('region_id')
                        ->from('user_region')
                        ->where('user_id', auth()->id());
                });
        }
        return $model->paginate(Answer::PAGE_SIZE);
    }

    public function getActiveMessage($type)
    {
        return Message::where([['status', '=', Message::STATUS_ACTIVE], ['type', '=', $type]])
            ->first();
    }
}
