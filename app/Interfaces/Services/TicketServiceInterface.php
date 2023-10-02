<?php

namespace App\Interfaces\Services;

interface TicketServiceInterface
{
    public function createTicket(array $request);
    public function updateTicket(array $request, $model): bool;
}
