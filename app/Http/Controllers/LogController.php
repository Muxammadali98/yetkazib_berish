<?php

namespace App\Http\Controllers;


use App\Repositories\LogRepository;

class LogController extends Controller
{
    public function __construct(protected LogRepository $logRepository)
    {
    }
    public function index()
    {
        return view('log.index', [
            'logs' => $this->logRepository->getLogs()
        ]);
    }
}
