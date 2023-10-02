<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Telegram extends Model
{
    use HasFactory;
    const TOKEN = '6247211570:AAHvObLvBcJRuMs27cONqiTTQB1vz9P2Tn0';
    const BASE_URL = 'https://api.telegram.org/bot'. self::TOKEN;
    const SERVER_URL = 'https://api.telegram.org';
    const UZ = 'uz';
    const RU = 'ru';


    public $url;
    public $ch;
    public $res;
    public function bot($method, $data = [])
    {
        $this->url = self::BASE_URL.'/'.$method;
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $this->res = curl_exec($this->ch);

        if(curl_error($this->ch))
        {
            var_dump(curl_error($this->ch));
        }
        else{
            return json_decode($this->res);
        }
    }

    public function getServerType(): string
    {
        return config('app.server_type', 'local');
    }

    /**
     * @param $file
     * @return string|void
     */
    public function sendFileType($file)
    {
        switch ($this->getServerType()) {
            case 'local':
                return 'file://' . public_path($file);
            case 'server':
                return asset($file);
        }
    }

    public function sendPhoto($chat_id, $photo, $caption = false, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('sendPhoto',[
            'chat_id' => $chat_id,
            'photo' => $this->sendFileType($photo),
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }


    public function sendMessage($chat_id, $text, $reply_markup = false, $message_id = false, $parse_mode = 'html')
    {
        $response = $this->bot('sendMessage',[
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
            'reply_to_message_id' => $message_id,
            'parse_mode' => $parse_mode
        ]);
        return $response;
    }

    public function deleteMessage($chat_id, $message_id)
    {
        $this->bot('deleteMessage',[
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
    }


    public function sendVideo($chat_id, $video, $caption = false, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('sendVideo',[
            'chat_id' => $chat_id,
            'video' => $this->sendFileType($video),
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }

    public function sendDocument($chat_id, $document, $caption = false, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('sendDocument',[
            'chat_id' => $chat_id,
            'document' => $this->sendFileType($document),
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }

    public function editMessageReplyMarkup($chat_id, $message_id, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('editMessageReplyMarkup',[
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }

    public function editMessageCaption($chat_id, $message_id, $caption, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('editMessageCaption',[
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }

    public function editMessageText($chat_id, $message_id, $text, $reply_markup = false, $parse_mode = 'html')
    {
        $response = $this->bot('editMessageText',[
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup
        ]);
        return $response;
    }

    public function answerCallbackQuery($data_id, $text, $show_alert = false)
    {
        $response = $this->bot('answerCallbackQuery',[
            'callback_query_id' => $data_id,
            'text' => $text,
            'show_alert' => $show_alert,
        ]);
        return $response;
    }

    public function sendChatAction($chat_id, $action)
    {
        $response = $this->bot('sendChatAction',[
            'chat_id' => $chat_id,
            'action' => $action,
        ]);
        return $response;
    }

    /**
     * $media example
     *
    [
    ['type' => 'photo', 'media' => 'attach://file1.png' ],
    ['type' => 'photo', 'media' => 'attach://file2.png' ],
    ]
     *
     */

    public function sendMediaGroup($chat_id, $media)
    {
        $response = $this->bot('sendMediaGroup',[
            'chat_id' => $chat_id,
            'media' => json_encode($media),
        ]);
        return $response;
    }

    public function getFile($file_id)
    {
        return $this->bot('getFile',[
            'file_id' => $file_id,
        ]);
    }

    public function replyKeyboardRemove($selective = false): bool|string
    {
        return json_encode([
            'remove_keyboard' => true,
            'selective' => $selective
        ]);
    }

    public function selectionLanguage($chat_id)
    {
        $step = Step::where('chat_id', $chat_id)->first();
        if (!is_null($step))
            if ($step->step_lang == Step::STEP_LANG_UZ_SELECTED) return App::setLocale(self::UZ);
            elseif ($step->step_lang == Step::STEP_LANG_RU_SELECTED) return App::setLocale(self::RU);
        return false;
    }

    public function changeLanguage($chat_id, $data)
    {
        $step = Step::where('chat_id', $chat_id)->first();
        if ($data == self::UZ) {
            $step->step_lang = Step::STEP_LANG_UZ_SELECTED;
            return $step->save();
        }
        elseif ($data == self::RU) {
            $step->step_lang = Step::STEP_LANG_RU_SELECTED;
            return $step->save();
        }
    }

    public function newClient($chat_id): false|\App\Models\Client
    {
        return Client::create([
            'chat_id' => $chat_id,
            'status' => Client::STATUS_INACTIVE,
        ]);
    }
}
