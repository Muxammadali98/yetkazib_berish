<?php

namespace App\Repositories;

use App\Models\Log;

class LogRepository implements \App\Interfaces\Repositories\LogRepositoryInterface
{

    public function getLogs()
    {
        return Log::with(['user' => function($query) {
            $query->select(['id', 'name'])->with('roles');
        }, 'application.client' => function($query){
            $query->select(['id', 'full_name']);
        }])->orderBy('id', 'desc')->paginate(Log::PAGE_SIZE);
    }
}
