<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository implements \App\Interfaces\Repositories\QuestionRepositoryInterface
{

    public function getAllQuestions()
    {
        return Question::orderBy('step')->paginate(Question::PAGE_SIZE);
    }

    public function getQuestion($id)
    {
        return Question::with('buttons')->findOrFail($id);
    }
}
