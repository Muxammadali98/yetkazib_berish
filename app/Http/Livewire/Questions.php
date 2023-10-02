<?php

namespace App\Http\Livewire;

use App\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Interfaces\Services\QuestionServiceInterface;
use App\Models\Question;
use Livewire\Component;

class Questions extends Component
{
    public function render(QuestionRepositoryInterface $questionRepository)

    {
        return view('livewire.questions', [
            'questions' => $questionRepository->getAllQuestions(),
        ]);
    }

    public function updateQuestionStep(QuestionServiceInterface $questionService, $list)
    {
        $questionService->updateStep($list);
    }

    public function changeStatus(QuestionServiceInterface $questionService, $id)
    {
        $questionService->updateStatus($id);
    }
}
