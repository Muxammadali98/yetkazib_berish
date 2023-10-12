<?php

namespace App\Interfaces\Services;

interface AnswerServiceInterface
{
    public function managerCancel($application_id);

    public function contractCancel($application_id);
    public function managerAccepted($application_id);
    public function contractAccepted($application_id);
    public function strReplace($target, $var, $text);
    public function messagePrepare($application_id, $message);
    public function send($application_id, $message);
    public function stripTags($text);
}
