<?php

namespace App\Http\Controllers;

use App\Interfaces\Repositories\TelegramRepositoryInterface;
use App\Interfaces\Services\TelegramServiceInterface;
use App\Models\Answer;
use App\Models\Start;
use App\Models\Step;
use App\Models\Telegram;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\App;

class TelegramController extends Controller
{
    protected TelegramRepositoryInterface $telegramRepository;
    protected TelegramServiceInterface $telegramService;

    public function __construct(TelegramRepositoryInterface $telegramRepository, TelegramServiceInterface $telegramService)
    {
        $this->telegramRepository = $telegramRepository;
        $this->telegramService = $telegramService;
    }
    public function index()
    {
//         return true;

        $model = new Telegram();
        $update = file_get_contents('php://input');
        $update = json_decode($update);
        $message = " | ";
        $text = " | ";
        $chat_id = " | ";
        $type = " | ";
        $first_name = " | ";
        $user_name = " | ";
        $last_name = "";
        $data = " | ";
        $message_id = " | ";
        if (isset($update->message)) {
            $message = $update->message;
            $message_id = $message->message_id;
            $chat_id = $message->chat->id;
            $type = $message->chat->type;
            $first_name = $message->chat->first_name;
            if (isset($message->chat->username)) {
                $user_name = $message->chat->username;
            }
            if (isset($message->chat->last_name)) {
                $last_name = $message->chat->last_name;
            }
            if (isset($message->text)) {
                $text = $message->text;
            }
            if (isset($message->caption)) {
                $caption = $message->caption;
            }
        } elseif (isset($update->callback_query)) {
            $data = $update->callback_query->data;
            $chat_id = $update->callback_query->message->chat->id;
            $message_id = $update->callback_query->message->message_id;
            if (isset($update->callback_query->id)) {
                $data_id = $update->callback_query->id;
            }
            $dataExplode = explode('_', $data);
        } elseif (isset($update->inline_query)) {
            $inline_query = $update->inline_query;
            $inline_id = $inline_query->id;
            $chat_type = $inline_query->chat_type;
            $from = $inline_query->from;
            $chat_id = $from->id;
            $query = $inline_query->query;
        } elseif (isset($update->channel_post)) {
            $message_id = $update->channel_post->message_id;
            $chat_id = $update->channel_post->chat->id;
            $user_name = $update->channel_post->chat->username;
            $text = $update->channel_post->text;
        }

        $teg = strrpos($text, '<');

        if ($teg !== false) {
            $model->deleteMessage($chat_id, $message_id);
        } else {
            $model->selectionLanguage($chat_id);
            $language_text = '🇺🇿 O\'zingizga qulay bo\'lgan tilni tanlang!' . PHP_EOL .
                '🇷🇺 Выберите язык';
            $language_keyboard = json_encode([
                'resize_keyboard' => true,
                'inline_keyboard' => [
                    [['text' => 'O\'zbek tili 🇺🇿', 'callback_data' => Telegram::UZ], ['text' => 'Русский язык 🇷🇺', 'callback_data' => Telegram::RU]],
                ]
            ]);

            $step = Step::where('chat_id', $chat_id)->first();
            if (isset($step)) {
                $step_level = $step->step;
                $client_id = $step->client_id;
                $question_type = $step->question_type;
                $region_id = $step->region_id;
            }

            if ($text == '/start') {
                $select_client = Client::where('chat_id', $chat_id)->first();
                if (!isset($select_client)) {
                    $newClient = $model->newClient($chat_id);
                    Step::setStep($chat_id, $newClient->id);
                } else {
                    $step->step = Step::STEP_UNIDENTIFIED;
                    $step->question_type = null;
                    $step->save();
                    $this->telegramService->removeAnswerCache($select_client->id);
                }
                $model->sendMessage($chat_id, $language_text, $language_keyboard);
            } elseif (isset($step_level) && $step_level == Step::STEP_UNIDENTIFIED && $text != "/start") {
                if ($data == Telegram::UZ || $data == Telegram::RU) {
                    App::setLocale($data);
                    if ($model->changeLanguage($chat_id, $data) && !$this->register($chat_id, $model, $step, $message_id, $client_id)) {
                        $this->homeMenu($chat_id, $message_id, $model, $step);
                    }
                } else {
                    $model->deleteMessage($chat_id, $message_id);
                }
            } elseif (isset($step_level) && $step_level == Step::SET_FULL_NAME && $text != "/start") {
                $this->telegramService->setFullName($client_id, $text);
                $model->sendMessage($chat_id, __('telegram.enter-phone-number'), $this->telegramRepository->phoneKeyboard());
                $step->step = Step::SET_PHONE_NUMBER;
                $step->save();
                return true;
            } elseif (isset($step_level) && $step_level == Step::SET_PHONE_NUMBER && $text != "/start") {
                if (isset($message->contact)) {
                    $phone_number = $message->contact->phone_number;
                    $this->telegramService->setPhoneNumber($client_id, $phone_number);
                    $this->getRegions($model, $chat_id, $step);
                } elseif(isset($text) && $text != ' | ' && preg_match('/^\+998\d{9}$/', $text)) {
                    $this->telegramService->setPhoneNumber($client_id, $text);
                    $this->getRegions($model, $chat_id, $step);
                } else {
                    $model->sendMessage($chat_id, __('telegram.enter-phone-number'), $this->telegramRepository->phoneKeyboard());
                }
            } elseif (isset($step_level) && $step_level == Step::HOME_MENU && $text != "/start") {
                $this->homeStep($text, $chat_id, $model, $step);
            } elseif (isset($step_level) && $step_level == Step::OUR_STORES && $text != "/start") {
                if (isset($dataExplode[0]) && $dataExplode[0] == 'region') {
                    $region_id = $dataExplode[1];
                    $branch = $this->telegramRepository->getBranchInfo($region_id);
                    $model->sendMessage($chat_id, $branch['text'], $branch['keyboard']);
                } elseif ($data == 'home') {
                    $this->homeMenu($chat_id, $message_id, $model, $step);
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'selectRegion') {
                    $this->telegramService->setRegionToStepTable($this->telegramRepository->getActiveQuestionType(), $dataExplode[1], $chat_id);
                    $model->answerCallbackQuery($data_id, __('telegram.region-selected'), true);
                } else {
                    $this->homeStep($text, $chat_id, $model, $step);
                }
            } elseif (isset($step_level) && $step_level == Step::CATEGORY && $text != "/start") {
                if (isset($dataExplode[0]) && $dataExplode[0] == 'category') {
                    $category_id = $dataExplode[1];
                    if ($this->telegramRepository->hasSubCategory($category_id)) {
                        $model->editMessageText($chat_id, $message_id, __('telegram.category-text'), $this->telegramRepository->getSubCategoryKeyboardWithPagination($category_id));
                        $step->step = Step::SUB_CATEGORY;
                    } else {
                        $category_info = $this->telegramRepository->getCategoryInfo($category_id);
                        $model->editMessageText($chat_id, $message_id, $category_info['text'], $category_info['keyboard']);
                        $this->toOrder($region_id, $chat_id, $model, $step, $client_id);
                        $step->step = Step::CATEGORY_INFO;
                    }
                    $step->save();
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'categoryPage') {
                    $page = $dataExplode[1];
                    $model->editMessageText($chat_id, $message_id, __('telegram.category-text'), $this->telegramRepository->getCategoryKeyboardWithPagination($page));
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'close') {
                    $this->homeMenu($chat_id, $message_id, $model, $step);
                } else {
                    $model->deleteMessage($chat_id, $message_id);
                }
            } elseif (isset($step_level) && $step_level == Step::SUB_CATEGORY && $text != "/start") {
                if (isset($dataExplode[0]) && $dataExplode[0] == 'subCategory') {
                    $category_id = $dataExplode[1];
                    $category_info = $this->telegramRepository->getCategoryInfo($category_id);
                    $model->editMessageText($chat_id, $message_id, $category_info['text'], $category_info['keyboard']);
                    $this->toOrder($region_id, $chat_id, $model, $step, $client_id);
                    $step->step = Step::CATEGORY_INFO;
                    $step->save();
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'subCategoryPage') {
                    $category_id = $dataExplode[3];
                    $page = $dataExplode[1];
                    $model->editMessageText($chat_id, $message_id, __('telegram.category-text'), $this->telegramRepository->getSubCategoryKeyboardWithPagination($category_id, $page));
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'close') {
                    $this->homeMenu($chat_id, $message_id, $model, $step);
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'toBasicCategory') {
                    $model->editMessageText($chat_id, $message_id, __('telegram.category-text'), $this->telegramRepository->getCategoryKeyboardWithPagination());
                    $step->step = Step::CATEGORY;
                    $step->save();
                } else {
                    $model->deleteMessage($chat_id, $message_id);
                }
            } elseif (isset($step_level) && $step_level == Step::CATEGORY_INFO && $text != "/start") {
                if (isset($dataExplode[0]) && $dataExplode[0] == 'download') {
                    $category_id = $dataExplode[1];
                    $pdf = $this->telegramRepository->getPDF($category_id);
                    $model->sendDocument($chat_id, $pdf['file'], $pdf['caption']);
                } elseif (isset($dataExplode[0]) && $dataExplode[0] == 'close') {
                    $this->homeMenu($chat_id, $message_id, $model, $step);
                } elseif ($data == 'changeCategory') {
                    $this->telegramService->closeApplication($client_id);
                    $model->editMessageText($chat_id, $message_id, __('telegram.category-text'), $this->telegramRepository->getCategoryKeyboardWithPagination());
                    $step->step = Step::CATEGORY;
                    $step->save();
                }  elseif (isset($text) && $text != ' | ') {
                    $this->setAnswerNoButton($client_id, $region_id, $chat_id, $text, $model, $step);
                    $step->step = Step::QUESTION;
                    $step->save();
                } elseif (isset($caption)) {
                    $this->setAnswerNoButton($client_id, $region_id, $chat_id, $caption, $model, $step);
                    $step->step = Step::QUESTION;
                    $step->save();
                } else {
                    $model->deleteMessage($chat_id, $message_id);
                }
            } elseif (isset($step_level) && $step_level == Step::REGION && $text != "/start") {
                if (isset($dataExplode) && $dataExplode[0] == 'region') {
                    $this->telegramService->setRegionToStepTable($this->telegramRepository->getActiveQuestionType(), $dataExplode[1], $chat_id);
                    $this->telegramService->setActiveClient($client_id);
                    $this->homeMenu($chat_id, $message_id, $model, $step);
                } elseif ($data == 'close') {
                    $this->closeApplication($client_id, $chat_id, $model, $step);
                }
            } elseif (isset($step_level) && $step_level == Step::QUESTION && $text != "/start" && !is_null($region_id)) {
                if ($data == 'close') {
                    $this->closeApplication($client_id, $chat_id, $model, $step);
                }
                if (isset($question_type) && $question_type == \App\Models\Question::TYPE_WITH_A_BUTTON) {
                    $model->deleteMessage($chat_id, $message_id);
                    if (isset($dataExplode) && $dataExplode[0] == 'button') {
                        $this->telegramService->setAnswer($dataExplode[3], $region_id, $client_id, $chat_id, $this->telegramRepository->getButtonText($dataExplode[1]), $step->step_lang);
                        $question = $this->telegramRepository->getTempQuestion($client_id);
                        if (!empty($question)) {
                            $model->sendMessage($chat_id, $question['question'], $question['keyboard']);
                            $this->telegramService->editTempStatus($question['temp_id']);
                            $this->telegramService->setQuestionTypeToStepTable($question['type'], $chat_id);
                        } else {
                            $model->sendMessage($chat_id, $this->telegramRepository->checkApplicationData($client_id), $this->telegramRepository->checkKeyboard());
                            $step->step = Step::CHECK;
                            $step->save();
                        }
                    } elseif ($data == 'close') {
                        $this->closeApplication($client_id, $chat_id, $model, $step);
                    }
                } elseif (isset($question_type) && $question_type == \App\Models\Question::TYPE_NO_BUTTON) {
                    if ($text == __('telegram.close')) {
                        $this->closeApplication($client_id, $chat_id, $model, $step);
                    } elseif (isset($text) && $text != ' | ') {
                        $this->setAnswerNoButton($client_id, $region_id, $chat_id, $text, $model, $step);
                    } elseif (isset($caption)) {
                        $this->setAnswerNoButton($client_id, $region_id, $chat_id, $caption, $model, $step);
                    }
                } elseif (isset($question_type) && $question_type == \App\Models\Question::TYPE_PHOTO) {
                    if ($text == __('telegram.close')) {
                        $this->closeApplication($client_id, $chat_id, $model, $step);
                    } elseif (isset($message->photo)) {
                        $this->setAnswerWithPhoto($client_id, $region_id, $chat_id, $message, $model, $step);
                    } else {
                        $model->sendMessage($chat_id, __('telegram.send-photo-again'), $this->telegramRepository->closeKeyboard());
                    }
                } elseif (isset($question_type) && $question_type == \App\Models\Question::TYPE_PHONE_NUMBER) {
                    if ($text == __('telegram.close')) {
                        $this->closeApplication($client_id, $chat_id, $model, $step);
                    } elseif (isset($text) && $text != ' | ') {
                        if (preg_match(pattern: '/^\+998\d{9}$/', subject: $text)) {
                            $this->setAnswerNoButton($client_id, $region_id, $chat_id, $text, $model, $step);
                        } elseif (isset($message->contact)) {
                            $phone_number = $message->contact->phone_number;
                            $this->setAnswerNoButton($client_id, $region_id, $chat_id, $phone_number, $model, $step);
                        } else {
                            $model->sendMessage($chat_id, __('telegram.send-phone-number-again'), $this->telegramRepository->closeKeyboard());
                        }
                    } elseif (isset($message->contact)) {
                        $phone_number = $message->contact->phone_number;
                        $this->setAnswerNoButton($client_id, $region_id, $chat_id, $phone_number, $model, $step);
                    } else {
                        $model->sendMessage($chat_id, __('telegram.send-phone-number-again'), $this->telegramRepository->closeKeyboard());
                    }
                }
            } elseif (isset($step_level) && $step_level == Step::CHECK && $text != "/start") {
                if ($text == __('telegram.confirm')) {
                    $this->telegramService->answersAcceptance($client_id);
                    $model->sendMessage($chat_id, __('telegram.accept-application'), $this->telegramRepository->startKeyboard());
                    $step->step = Step::HOME_MENU;
                    $step->save();
                } elseif ($text == __('telegram.close')) {
                    $this->telegramService->closeApplication($client_id);
                    $model->sendMessage($chat_id, __('telegram.close-application'), $this->telegramRepository->startKeyboard());
                    $step->step = Step::HOME_MENU;
                    $step->save();
                } else {
                    $model->deleteMessage($chat_id, $message_id);
                }
            }
        }
    }

    protected function homeMenu($chat_id, $message_id, $model, $step)
    {
        $model->deleteMessage($chat_id, $message_id);
        $start_message = $this->telegramRepository->getStartMessage();
        if (isset($start_message)) {
            if ($start_message->type == Start::TYPE_TEXT) {
                $model->sendMessage($chat_id, $start_message->message, $this->telegramRepository->startKeyboard());
            } elseif ($start_message->type == Start::TYPE_PHOTO) {
                $model->sendPhoto($chat_id, 'uploads/images/' . $start_message->image, $start_message->message, $this->telegramRepository->startKeyboard());
            }
            $step->step = Step::HOME_MENU;
            $step->save();
        }
    }

    public function closeApplication($client_id, $chat_id, $model, $step)
    {
        $this->telegramService->closeApplication($client_id);
        $model->sendMessage($chat_id, __('telegram.close-application'), $this->telegramRepository->startKeyboard());
        $step->step = Step::HOME_MENU;
        $step->save();
    }

    public function setAnswerNoButton($client_id, $region_id, $chat_id, $text, $model, $step)
    {
        $this->telegramService->setAnswer($this->telegramRepository->getQuestionIdInTemp($client_id), $region_id, $client_id, $text, $step->step_lang);
        $question = $this->telegramRepository->getTempQuestion($client_id);
        if (!empty($question)) {
            $model->sendMessage($chat_id, $question['question'], ($question['keyboard']) ?: $this->telegramRepository->closeKeyboard());
            $this->telegramService->editTempStatus($question['temp_id']);
            $this->telegramService->setQuestionTypeToStepTable($question['type'], $chat_id);
        } else {
            $model->sendMessage($chat_id, $this->telegramRepository->checkApplicationData($client_id), $this->telegramRepository->checkKeyboard());
            $step->step = Step::CHECK;
            $step->save();
        }
    }

    public function setAnswerWithPhoto($client_id, $region_id, $chat_id, $message, $model, $step)
    {
        $this->telegramService->setAnswer($this->telegramRepository->getQuestionIdInTemp($client_id), $region_id, $client_id, $this->telegramRepository->getPhoto($message->photo), $step->step_lang);
        $question = $this->telegramRepository->getTempQuestion($client_id);
        if (!empty($question)) {
            $model->sendMessage($chat_id, $question['question'], ($question['keyboard']) ?: $this->telegramRepository->closeKeyboard());
            $this->telegramService->editTempStatus($question['temp_id']);
            $this->telegramService->setQuestionTypeToStepTable($question['type'], $chat_id);
        } else {
            $model->sendMessage($chat_id, $this->telegramRepository->checkApplicationData($client_id), $this->telegramRepository->checkKeyboard());
            $step->step = Step::CHECK;
            $step->save();
        }
    }

    public function register($chat_id, $model, $step, $message_id, $client_id): bool
    {
        if (!$this->telegramRepository->isActiveClient($client_id)) {
            $model->deleteMessage($chat_id, $message_id);
            $model->sendMessage($chat_id, __('telegram.register'));
            $model->sendMessage($chat_id, __('telegram.full-name'));
            $step->step = Step::SET_FULL_NAME;
            $step->save();
            return true;
        }
        return false;
    }

    public function homeStep($text, $chat_id, $model, $step)
    {
        if ($text == __('telegram.category')) {
            $model->sendMessage($chat_id, __('telegram.started'), $model->replyKeyboardRemove());
            $model->sendMessage($chat_id, __('telegram.category-text'), $this->telegramRepository->getCategoryKeyboardWithPagination());
            $step->step = Step::CATEGORY;
            $step->save();
        } elseif ($text == __('telegram.our-stores')) {
            $model->sendMessage($chat_id, __('telegram.our-stores'), $this->telegramRepository->getBranchesKeyboard());
            $step->step = Step::OUR_STORES;
            $step->save();
        } elseif ($text == __('telegram.our-channel')) {
            $model->sendMessage($chat_id, __('telegram.subscribe-to-our-channel'), $this->telegramRepository->channelKeyboard());
        }
    }

    public function getRegions($model, $chat_id, $step)
    {
        $model->sendMessage($chat_id, __('telegram.select-region'), $this->telegramRepository->getRegions());
        $step->step = Step::REGION;
        $step->save();
    }

    public function toOrder($region_id, $chat_id, $model, $step, $client_id)
    {
        $this->telegramService->setRegionToStepTable($this->telegramRepository->getActiveQuestionType(), $region_id, $chat_id);
        $this->telegramService->setQuestionToTempTable($this->telegramRepository->getQuestionsOrderByStep(), $client_id);
        $this->telegramService->setApplication($client_id);
        $question = $this->telegramRepository->getTempQuestion($client_id);
        if (!empty($question)) {
            $model->sendMessage($chat_id, $question['question'], ($question['keyboard']) ?: $this->telegramRepository->closeKeyboard());
            $this->telegramService->editTempStatus($question['temp_id']);
            $this->telegramService->setQuestionTypeToStepTable($question['type'], $chat_id);
        }
    }
}
