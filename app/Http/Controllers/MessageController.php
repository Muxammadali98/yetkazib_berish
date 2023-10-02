<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Interfaces\Repositories\MessageRepositoryInterface;
use App\Interfaces\Services\MessageServiceInterface;
use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Services\MessageService;

class MessageController extends Controller
{
    protected MessageRepository $messageRepository;
    protected MessageService $messageService;

    public function __construct(MessageRepositoryInterface $messageRepository, MessageServiceInterface $messageService)
    {
        $this->messageRepository = $messageRepository;
        $this->messageService = $messageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('message.index', [
            'messages' => $this->messageRepository->getAllMessages(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMessageRequest $request)
    {
        if ($this->messageService->createMessage($request))
            return redirect()->route('message.index');
        else
            return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('message.show', [
            'model' => $this->messageRepository->getMessage($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Message $message)
    {
        return view('message.update', [
            'model' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        if ($this->messageService->updateMessage($request, $message))
            return redirect()->route('message.index');
        else
            return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message)
    {
        $this->messageService->editStatus($message);
        return redirect()->back();
    }
}
