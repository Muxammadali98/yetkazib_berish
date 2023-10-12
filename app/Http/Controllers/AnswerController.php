<?php

namespace App\Http\Controllers;

use App\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Interfaces\Repositories\ApplicationCommentRepositoryInterface;
use App\Interfaces\Services\AnswerServiceInterface;
use App\Interfaces\Services\ApplicationCommentServiceInterface;
use App\Models\Answer;
use App\Models\ApplicationComment;
use App\Models\Message;
use PDF;

class AnswerController extends Controller
{
    public function __construct(
        protected AnswerRepositoryInterface $answerRepository,
        protected AnswerServiceInterface $answerService,
        protected ApplicationCommentServiceInterface $applicationCommentService,
        protected ApplicationCommentRepositoryInterface $applicationCommentRepository
    )
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function managerIndex()
    {
        return view('answer.manager.index', [
            'answers' => $this->answerRepository->getNewAnswers(Answer::MANAGER_NEW_ORDER)
        ]);
    }

    public function managerAcceptedIndex()
    {
        return view('answer.manager.accepted', ['answers' => $this->answerRepository->getAcceptedAnswers(Answer::STATUS_MANAGER_ACCEPTED)]);
    }

    public function managerCancelIndex()
    {
        return view('answer.manager.cancel', ['answers' => $this->answerRepository->getRejectedAnswers(Answer::STATUS_MANAGER_REJECTED)]);
    }

    public function show($application_id)
    {
        return view('answer.manager.show', [
            'answers' => $this->answerRepository->getAnswerByApplication($application_id),
            'images_src' => $this->answerRepository->getAnswerImages($application_id),
            'comments' => $this->applicationCommentRepository->getCommentsByApplication($application_id),
        ]);
    }


    public function createPDF($application_id)
    {
        $pdf = PDF::loadView('answer.manager.pdf', [
            'answers' => $this->answerRepository->getAnswerByApplication($application_id),
            'images_src' => $this->answerRepository->getAnswerImages($application_id),
        ]);
        return $pdf->download();
    }

    public function managerAccepted($application_id)
    {
        if (request()->ajax()) {
            if (request()->isMethod('GET')) {
                $view = view('answer._form')->render();
                return response()->json([
                    'status' => 'success',
                    'content' => $view
                ]);
            }

            if (request()->isMethod('POST')) {
                $this->answerService->managerAccepted($application_id);
                $this->applicationCommentService->create([
                    'application_id' => $application_id,
                    'comment' => request()->get('comment'),
                    'user_id' => auth()->id(),
                    'status' => ApplicationComment::STATUS_ACCEPTED
                ]);
                $this->answerService->send($application_id, $this->answerRepository->getActiveMessage(Message::TYPE_MANAGER_ACCEPTED));
                return response()->json([
                    'status' => 'success',
                    'content' => 'success'
                ]);
            }
        }
    }
    public function managerUpdate($application_id)
    {
        if (request()->ajax()) {
            if (request()->isMethod('GET')) {
                $job = 'Mahsulot nomi va artikul';
                $view = view('answer._form',compact('job'))->render();
                return response()->json([
                    'status' => 'success',
                    'content' => $view
                ]);
            }

            if (request()->isMethod('POST')) {
                $this->answerService->managerUpdateJob($application_id,request()->get('comment'));
                return response()->json([
                    'status' => 'success',
                    'content' => 'success'
                ]);
            }
        }
    }

    public function managerCancel($application_id)
    {
        if (request()->ajax()) {
            if (request()->isMethod('GET')) {
                $view = view('answer._form')->render();
                return response()->json([
                    'status' => 'success',
                    'content' => $view
                ]);
            }

            if (request()->isMethod('POST')) {
                $this->answerService->managerCancel($application_id);
                $this->applicationCommentService->create([
                    'application_id' => $application_id,
                    'comment' => request()->get('comment'),
                    'user_id' => auth()->id(),
                    'status' => ApplicationComment::STATUS_REJECTED
                ]);
                $this->answerService->send($application_id, $this->answerRepository->getActiveMessage(Message::TYPE_MANAGER_REJECTED));
                return response()->json([
                    'status' => 'success',
                    'content' => 'success'
                ]);
            }
        }
    }

    public function contractIndex()
    {
        return view('answer.contract.index', [
            'answers' => $this->answerRepository->getNewAnswers(Answer::CONTRACT_NEW_ORDER)
        ]);
    }

    public function contractAccepted($application_id)
    {
        if (request()->ajax()) {
            if (request()->isMethod('GET')) {
                $view = view('answer._form')->render();
                return response()->json([
                    'status' => 'success',
                    'content' => $view
                ]);
            }

            if (request()->isMethod('POST')) {
                $this->answerService->contractAccepted($application_id);
                $this->applicationCommentService->create([
                    'application_id' => $application_id,
                    'comment' => request()->get('comment'),
                    'user_id' => auth()->id(),
                    'status' => ApplicationComment::STATUS_ACCEPTED
                ]);
                $this->answerService->send($application_id, $this->answerRepository->getActiveMessage(Message::TYPE_CONTRACT_ACCEPTED));
                return response()->json([
                    'status' => 'success',
                    'content' => 'success'
                ]);
            }
        }
    }

    public function contractAcceptedIndex()
    {
        return view('answer.contract.accepted', ['answers' => $this->answerRepository->getAcceptedAnswers(Answer::STATUS_CONTRACT_ACCEPTED)]);
    }

    public function contractCancel($application_id)
    {
        if (request()->ajax()) {
            if (request()->isMethod('GET')) {
                $view = view('answer._form')->render();
                return response()->json([
                    'status' => 'success',
                    'content' => $view
                ]);
            }

            if (request()->isMethod('POST')) {
                $this->answerService->contractCancel($application_id);
                $this->applicationCommentService->create([
                    'application_id' => $application_id,
                    'comment' => request()->get('comment'),
                    'user_id' => auth()->id(),
                    'status' => ApplicationComment::STATUS_REJECTED
                ]);
                $this->answerService->send($application_id, $this->answerRepository->getActiveMessage(Message::TYPE_CONTRACT_REJECTED));
                return response()->json([
                    'status' => 'success',
                    'content' => 'success'
                ]);
            }
        }
    }

    public function contractCancelIndex()
    {
        return view('answer.contract.cancel', ['answers' => $this->answerRepository->getRejectedAnswers(Answer::STATUS_CONTRACT_REJECTED)]);
    }
}
