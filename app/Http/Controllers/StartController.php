<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStartRequest;
use App\Http\Requests\UpdateStartRequest;
use App\Interfaces\Repositories\StartRepositoryInterface;
use App\Interfaces\Services\StartServiceInterface;
use App\Models\Start;
use Illuminate\Http\Request;

class StartController extends Controller
{
    private StartRepositoryInterface $startRepository;
    private StartServiceInterface $startService;

    public function __construct(StartRepositoryInterface $startRepository, StartServiceInterface $startService)
    {
        $this->startRepository = $startRepository;
        $this->startService = $startService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('start.index', [
            'messages' => $this->startRepository->getAllStartMessages(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('start.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreStartRequest $request)
    {
        if ($this->startService->createStartMessage($request))
            return redirect()->route('start.index');
        else
            return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('start.show', [
            'model' => $this->startRepository->getStartMessage($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('start.update', [
            'model' => $this->startRepository->getStartMessageForEdit($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStartRequest $request, $id)
    {
        $model = $this->startRepository->getStartMessageForEdit($id);
        if ($this->startService->updateStartMessage($request, $model))
            return redirect()->route('start.index');
        else
            return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->startService->editStatus($id);
        return redirect()->back();
    }
}
