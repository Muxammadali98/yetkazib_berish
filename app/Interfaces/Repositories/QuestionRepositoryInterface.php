<?php

namespace App\Interfaces\Repositories;

interface QuestionRepositoryInterface
{
    public function getAllQuestions();
    public function getQuestion($id);
}
