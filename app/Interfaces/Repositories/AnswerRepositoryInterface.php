<?php

namespace App\Interfaces\Repositories;

use App\Models\Answer;

interface AnswerRepositoryInterface
{
    public function getNewAnswers($status);
    public function getAnswerByApplication($application_id);
    public function getAnswerImages($application_id);
    public function getAcceptedAnswers($status);
    public function getRejectedAnswers($status);
    public function getActiveMessage($type);
}
