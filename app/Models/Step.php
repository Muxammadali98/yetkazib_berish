<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    const SET_FULL_NAME = 8;
    const SET_PHONE_NUMBER = 9;
    protected $guarded = [];
    const STEP_LANG_UZ_SELECTED = 1;
    const STEP_LANG_RU_SELECTED = 2;
    const STEP_UNIDENTIFIED = 0;
    const HOME_MENU = 1;
    const CATEGORY = 2;
    const SUB_CATEGORY = 3;
    const CATEGORY_INFO = 4;
    const REGION = 5;
    const QUESTION = 6;
    const CHECK = 7;
    const OUR_STORES = 10;


    public static function setStep($chat_id, $client_id)
    {
        $model = self::where('chat_id', $chat_id)->first();
        if (!$model) {
            $step = new self();
            $step->client_id = $client_id;
            $step->chat_id = $chat_id;
            return $step->save();
        }
    }
}
