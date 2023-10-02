<?php

namespace App\Interfaces\Api\Repositories;

interface TicketRepositoryInterface
{
    public function getTickets($status, $type);
    public function getDoneTickets($type, $from, $to);
}
