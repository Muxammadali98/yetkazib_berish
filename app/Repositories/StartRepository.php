<?php

namespace App\Repositories;



use App\Models\Start;

class StartRepository implements \App\Interfaces\Repositories\StartRepositoryInterface
{

    public function getAllStartMessages()
    {
        return Start::orderByDesc('status')->paginate(Start::PAGE_SIZE);
    }

    public function getStartMessage($id)
    {
        return Start::findOrFail($id);
    }

    public function getStartMessageForEdit($id)
    {
        return Start::findOrFail($id);
    }
}
