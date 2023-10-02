<?php

namespace App\Interfaces\Api\Services;

use App\Models\Ticket;

interface TicketServiceInterface
{

    public function setProgress(int $id): bool;
    public function done(array $request): bool;
    public function doneByReturn(array $request): bool;
}
