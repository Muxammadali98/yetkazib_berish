<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Application;
use App\Models\Button;
use App\Models\Category;
use App\Models\Client;
use App\Models\Question;
use App\Models\Region;
use App\Models\Start;
use App\Models\Temp;
use App\Services\TelegramService;

class TelegramRepository implements \App\Interfaces\Repositories\TelegramRepositoryInterface
{

    public function getStartMessage()
    {
        return Start::select(['message_' . app()->getLocale() . ' AS message', 'image', 'type'])->where('status', Start::STATUS_ACTIVE)->first();
    }

    public function startKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.category')],
                ['text' => __('telegram.our-stores')],
            ],
            [
                ['text' => __('telegram.our-channel')],
            ],
        ];
        return json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]);
    }

    public function closeKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.close'), 'callback_data' => 'close'],
            ],
        ];
        return json_encode(['inline_keyboard' => $keyboard, 'resize_keyboard' => true]);
    }

    public function getActiveQuestionType()
    {
        return Question::select(['type'])->where('status', Question::STATUS_ACTIVE)->orderBy('step')->first();
    }

    public function getQuestionsOrderByStep()
    {
        return Question::select(['id'])->where('status', Question::STATUS_ACTIVE)->orderBy('step')->get();
    }

    public function getTempQuestion($client_id): array
    {
        $question = Temp::select(['id','question_id'])->with(['question' => function($query){
            $query->select(['id', 'title_' . app()->getLocale() . ' AS title', 'pdf_title_' . app()->getLocale() . ' AS pdf_title', 'type', 'step'])
                ->with(['buttons' => function($query){
                    $query->select(['id', 'question_id', 'text_' . app()->getLocale() . ' AS text']);
                }]);
        }])->where([['client_id', '=', $client_id],['status', '=', Temp::STATUS_INACTIVE]])->first();
        if (isset($question)) {
            if ($question->question->type == Question::TYPE_WITH_A_BUTTON && isset($question->question->buttons)) {
                $keyboard = [];
                foreach ($question->question->buttons as $button) {
                    $keyboard[] = [['text' => $button->text, 'callback_data' => 'button_' . $button->id . '_question_' . $question->question_id]];
                }
                $keyboard = !empty($keyboard) ? json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]) : false;
            } elseif ($question->question->type == Question::TYPE_PHONE_NUMBER) {
                $keyboard = $this->phoneKeyboard();
            }
            return [
                'temp_id' => $question->id,
                'question' => $question->question->title,
                'pdf_title' => $question->question->pdf_title,
                'type' => $question->question->type,
                'step' => $question->question->step,
                'keyboard' => $keyboard ?? false,
            ];
        }
        return [];
    }

    public function getButtonText($id)
    {
        $button = Button::select(['text_' . app()->getLocale() . ' AS text'])->where('id', $id)->first();
        return $button->text;
    }

    public function checkKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.confirm')],
            ],
            [
                ['text' => __('telegram.close')],
            ],
        ];
        return json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]);
    }

    public function checkApplicationData($client_id): string
    {
        $application = $this->getInactiveApplication($client_id);
        $answers = Answer::where([['client_id', '=', $client_id],['application_id', '=', $application->id],['status', '=', Answer::STATUS_INACTIVE]])
            ->with(['question' => function($query){
                $query->select(['id', 'pdf_title_' . app()->getLocale() . ' AS pdf_title', 'type']);
            }])->get();
        $data = '';
        if (isset($answers)) {
            $data .= __('telegram.your-information') . PHP_EOL . PHP_EOL;
            foreach ($answers as $answer) {
                if ($answer->question->type != Question::TYPE_PHOTO) {
                    $data .= '<b>- ' . $answer->question->pdf_title . '</b> ' . $answer->text . PHP_EOL;
                }
            }
            $data .= PHP_EOL . __('telegram.confirm-your-information');
        }
        return $data;
    }

    public function getInactiveApplication($client_id)
    {
        return Application::where([['client_id', '=', $client_id],['status', '=', Application::STATUS_INACTIVE]])->first();
    }

    public function getQuestionIdInTemp($client_id)
    {
        $temp = Temp::select(['question_id'])->where([['client_id', '=', $client_id],['status', '=', Temp::STATUS_ACTIVE]])->orderByDesc('id')->first();
        return $temp->question_id;
    }

    public function getPhoto($photos)
    {
        if (isset($photos[4])) {
            return $photos[4]->file_id;
        } elseif (isset($photos[3])) {
            return $photos[3]->file_id;
        } elseif (isset($photos[2])) {
            return $photos[2]->file_id;
        } elseif (isset($photos[1])) {
            return $photos[1]->file_id;
        } elseif (isset($photos[0])) {
            return $photos[0]->file_id;
        }
    }

    public function phoneKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.send-phone'), 'request_contact' => true],
            ],
        ];
        return json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]);
    }

    public function getCategoryKeyboardWithPagination($page = 1): bool|string
    {
        $categories = Category::select(['id', 'title_' . app()->getLocale() . ' AS title'])
            ->where('status', Category::STATUS_ACTIVE)
            ->where('step', Category::CATEGORY)
            ->orderBy('id')
            ->paginate(Category::TELEGRAM_PAGE_SIZE, ['*'], 'page', $page);
        $keyboard = [];
        $temp = [];
        foreach ($categories as $category) {
            $temp[] = ['text' => $category->title, 'callback_data' => 'category_' . $category->id];
            if (count($temp) == 2) {
                $keyboard[] = $temp;
                $temp = [];
            }
        }
        if (count($temp) == 1) {
            $keyboard[] = $temp;
        }
        if ($categories->hasMorePages()) {
            $keyboard[] = [['text' => __('telegram.next'), 'callback_data' => 'categoryPage_' . ($page + 1)]];
        }
        if ($page > 1) {
            $keyboard[] = [['text' => __('telegram.previous'), 'callback_data' => 'categoryPage_' . ($page - 1)]];
        }
        $keyboard[] = [['text' => __('telegram.close'), 'callback_data' => 'close']];
        return json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
    }

    public function getSubCategoryKeyboardWithPagination($category_id, $page = 1): bool|string
    {
        $categories = Category::select(['id', 'title_' . app()->getLocale() . ' AS title'])
            ->where('status', Category::STATUS_ACTIVE)
            ->where('step', Category::SUB_CATEGORY)
            ->where('parent_id', $category_id)
            ->orderBy('id')
            ->paginate(Category::TELEGRAM_PAGE_SIZE, ['*'], 'page', $page);
        $keyboard = [];
        $temp = [];
        foreach ($categories as $category) {
            $temp[] = ['text' => $category->title, 'callback_data' => 'subCategory_' . $category->id];
            if (count($temp) == 2) {
                $keyboard[] = $temp;
                $temp = [];
            }
        }
        if (count($temp) == 1) {
            $keyboard[] = $temp;
        }
        if ($categories->hasMorePages()) {
            $keyboard[] = [['text' => __('telegram.next'), 'callback_data' => 'subCategoryPage_' . ($page + 1) . '_category_' . $category_id]];
        }
        if ($page > 1) {
            $keyboard[] = [['text' => __('telegram.previous'), 'callback_data' => 'subCategoryPage_' . ($page - 1) . '_category_' . $category_id]];
        }
        $keyboard[] = [['text' => __('telegram.to-basic-category'), 'callback_data' => 'toBasicCategory'],['text' => __('telegram.close'), 'callback_data' => 'close']];
        return json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
    }

    public function hasSubCategory($category_id)
    {
        return Category::where([['parent_id', '=', $category_id],['status', '=', Category::STATUS_ACTIVE]])->exists();
    }

    public function getCategoryInfo($category_id): array
    {
        $category = Category::select(['id', 'title_' . app()->getLocale() . ' AS title', 'channel_link',])
            ->where([['id', '=', $category_id],['status', '=', Category::STATUS_ACTIVE]])
            ->first();
        $keyboard = [
            [
                ['text' => __('telegram.to-channel'), 'url' => $category->channel_link],
                ['text' => __('telegram.download-pdf'), 'callback_data' => 'download_' . $category->id]
            ],
            [
                ['text' => __('telegram.change-category'), 'callback_data' => 'changeCategory'],
            ],
        ];
        return [
            'text' => str_replace('[[category]]', $category->title, __('telegram.selected-category-info')),
            'keyboard' => json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]),
        ];
    }

    public function getPDF($category_id): array
    {
        $category = Category::select(['id', 'title_' . app()->getLocale() . ' AS title', 'file'])
            ->where([['id', '=', $category_id],['status', '=', Category::STATUS_ACTIVE]])
            ->first();
        return [
            'caption' => $category->title,
            'file' => 'uploads/documents/' . $category->file
        ];
    }

    public function isActiveClient($client_id)
    {
        return Client::where([['id', '=', $client_id],['status', '=', Client::STATUS_ACTIVE]])->exists();
    }

    public function getRegions(): bool|string
    {
        $regions = Region::select(['id', 'name_' . app()->getLocale() . ' AS name'])->get();
        $keyboard = [];
        foreach ($regions as $region) {
            $keyboard[] = [['text' => $region->name, 'callback_data' => 'region_' . $region->id]];
        }
        return json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
    }

    public function getBranchesKeyboard(): bool|string
    {
        $regions = Region::select(['id', 'name_' . app()->getLocale() . ' AS name'])->get();
        $keyboard = [];
        foreach ($regions as $region) {
            $keyboard[] = [['text' => $region->name, 'callback_data' => 'region_' . $region->id]];
        }
        return $keyboard ? json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]) : false;
    }

    public function homeKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.home'), 'callback_data' => 'home'],
            ],
        ];
        return json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
    }

    public function getBranchInfo($region_id): array
    {
        $telegramService = new TelegramService();
        $region = Region::select(['id', 'name_' . app()->getLocale() . ' AS name', 'body_' . app()->getLocale() . ' AS body', 'address_link'])
            ->where([['id', '=', $region_id],['status', '=', Region::STATUS_ACTIVE]])
            ->first();
        $text = '';
        if (isset($region)) {
            $text .= '<b><a href="' . $region->address_link . '">' . $region->name . '</a></b>' . PHP_EOL . PHP_EOL;
            $text .= $telegramService->stripTags($region->body);
        }
        $keyboard = [
            [
                ['text' => __('telegram.select-region-for-order'), 'callback_data' => 'selectRegion_' . $region_id],
            ]
        ];
        $keyboard = json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
        return [
            'text' => $text,
            'keyboard' => $keyboard,
        ];
    }

    public function channelKeyboard(): bool|string
    {
        $keyboard = [
            [
                ['text' => __('telegram.orzu-grand-savdo'), 'url' => __('telegram.channel-link')],
            ],
        ];
        return json_encode(['resize_keyboard' => true, 'inline_keyboard' => $keyboard]);
    }
}
