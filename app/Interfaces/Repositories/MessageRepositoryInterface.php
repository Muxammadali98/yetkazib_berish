<?php

namespace App\Interfaces\Repositories;

interface MessageRepositoryInterface
{
    public function getAllMessages();
    public function getMessage($id);
    public function getMessageForEdit($id);
}
