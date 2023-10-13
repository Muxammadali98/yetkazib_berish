<?php

namespace App\Repositories;

use App\Interfaces\Repositories\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\User;

class TicketRepository implements TicketRepositoryInterface
{
    public function getOpenTickets()
    {
        return Ticket::where('status', Ticket::STATUS_OPEN)
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['car:id,name'])
            ->paginate(Ticket::PAGE_SIZE);
    }

    public function getDelivery()
    {
        return User::where('status', User::STATUS_ACTIVE)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'delivery');
            })
            ->select('id', 'name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getTicket(int $id): Ticket|null
    {
        return Ticket::where('id', $id)
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['products' => function ($query) {
                $query->select('id', 'ticket_id', 'product_name', 'article', 'model', 'quantity');
            }])
            ->with(['supplierAction' => function ($query) {
                $query->select('id', 'ticket_id', 'comment', 'lat', 'lng','created_at')
                    ->with(['supplierFiles:id,supplier_action_id,file']);
            }])
            ->first();
    }

    public function getTrashTickets(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Ticket::onlyTrashed()
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate(Ticket::PAGE_SIZE);
    }

    public function getAcceptedTickets(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Ticket::where('status', Ticket::STATUS_IN_PROGRESS)
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate(Ticket::PAGE_SIZE);
    }

    public function getClosedTickets(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Ticket::where('status', Ticket::STATUS_CLOSED)
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])->orderBy('created_at','DESC')
            ->paginate(Ticket::PAGE_SIZE);
    }
}
