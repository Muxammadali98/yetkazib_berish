<?php

namespace App\Interfaces\Repositories;

interface TicketRepositoryInterface
{
    public function getOpenTickets();
    public function getDelivery();
    public function getTicket(int $id);
    public function getTrashTickets();
    public function getAcceptedTickets();
    public function getClosedTickets();
}
