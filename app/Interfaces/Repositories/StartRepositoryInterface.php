<?php

namespace App\Interfaces\Repositories;

use App\Models\Start;

interface StartRepositoryInterface
{
    public function getAllStartMessages();
    public function getStartMessage($id);
    public function getStartMessageForEdit($id);
}
