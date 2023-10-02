<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Interfaces\Services\QuestionServiceInterface;
use App\Models\Question;
use Illuminate\Http\Request;
class QuestionController extends Controller
{
    private QuestionRepositoryInterface $questionRepository;
    private QuestionServiceInterface $questionService;

    public function __construct(QuestionRepositoryInterface $questionRepository, QuestionServiceInterface $questionService)
    {
        $this->questionRepository = $questionRepository;
        $this->questionService = $questionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('question.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionRequest  $request
     * @return string
     */
    public function store(StoreQuestionRequest $request)
    {
        if ($this->questionService->createQuestion($request)) {
            return redirect()->route('question.index');
        }
        return redirect()->back().withError($request->validator).withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('question.show', ['model' => $this->questionRepository->getQuestion($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Question $question)
    {
        return view('question.update', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return string
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        if ($this->questionService->updateQuestion($request, $question)) {
            return redirect()->route('question.index');
        }
        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    public function destroy(Request $request)
    {
        $this->questionService->updateStatus($request->id);
        return response()->json([
            'status' => 'success',
        ]);
    }


    public function createButton(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $count = $request['item'];
        $html = view('question.create-button',['i' => $count])->render();
        return response()->json([
            'status' => 'success',
            'content' => $html
        ]);
    }
}
