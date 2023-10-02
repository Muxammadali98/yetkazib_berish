<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository implements \App\Interfaces\Repositories\MessageRepositoryInterface
{
    public function getAllMessages()
    {
        return Message::orderByDesc('status')->paginate(Message::PAGE_SIZE);
    }

    public function getMessage($id)
    {
        return Message::findOrFail($id);
    }

    public function getMessageForEdit($id)
    {
        return Message::findOrFail($id);
    }
}
