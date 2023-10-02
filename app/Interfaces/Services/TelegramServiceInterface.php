<?php

namespace App\Interfaces\Services;

interface TelegramServiceInterface
{
    public function setRegionToStepTable($question, $region_id, $chat_id);
    public function setQuestionToTempTable($questions, $client_id);
    public function removeTempTable($client_id);
    public function setApplication($client_id);
    public function editTempStatus($id);
    public function setAnswer($question_id, $region_id, $client_id, $answer, $language);
    public function removeAnswerCache($client_id);
    public function setQuestionTypeToStepTable($type, $chat_id);
    public function answersAcceptance($client_id);
    public function closeApplication($client_id);
    public function setFullName($client_id, $full_name);
    public function setPhoneNumber($client_id, $phone_number);
    public function setActiveClient($client_id);
    public function stripTags($text);
}
