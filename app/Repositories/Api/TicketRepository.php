<?php

namespace App\Repositories\Api;

use App\Models\Ticket;

class TicketRepository implements \App\Interfaces\Api\Repositories\TicketRepositoryInterface
{
    public function getTickets($status, $type)
    {
        return Ticket::select(['id', 'car_id', 'user_id', 'address', 'phone', 'client_name', 'comment', 'contract_id', 'additional_phone', 'created_at'])
            ->with(['user:id,name', 'products:id,ticket_id,product_name,article,model,quantity', 'car:id,name,color,number'])
            ->where('status', $status)
            ->where('type', $type)
            ->where('user_id', auth()->user()->id)
            ->paginate(Ticket::PAGE_SIZE);
    }

    public function getDoneTickets($type, $from = null, $to = null)
    {
        if ($from && $to) {
            $from = date('Y-m-d', strtotime($from)) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($to)) . ' 23:59:59';
        } else {
            $from = date('Y-m-d') . ' 00:00:00';
            $to = date('Y-m-d') . ' 23:59:59';
        }
        return Ticket::select(['id', 'car_id', 'user_id', 'address', 'phone', 'client_name', 'comment', 'contract_id', 'additional_phone', 'created_at'])
            ->with([
                'user:id,name',
                'products:id,ticket_id,product_name,article,model,quantity',
                'car:id,name,color,number',
                'supplierAction' => function ($query) {
                    $query->select(['id', 'ticket_id', 'lat', 'lng', 'comment'])->with(['supplierFiles:id,supplier_action_id,file']);
                },
            ])
            ->where('status', Ticket::STATUS_CLOSED)
            ->where('type', $type)
            ->where('user_id', auth()->user()->id)
            ->whereBetween('updated_at', [$from, $to])
            ->paginate(Ticket::PAGE_SIZE);
    }
}
