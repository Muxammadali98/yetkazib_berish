<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketService implements \App\Interfaces\Services\TicketServiceInterface
{
    public function createTicket(array $request)
    {
        DB::beginTransaction();
        try {
            $ticket = [
                'user_id' =>$request['user_id'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'contract_id' => $request['contract_id'],
                'additional_phone' => $request['additional_phone'],
                'client_name' => $request['client_name'],
                'comment' => $request['comment'],
                'status' => Ticket::STATUS_OPEN,
                'type' => $request['type'],
                'car_id' => $request['car_id'],
            ];
            $ticket = Ticket::create($ticket);

            $products = [];
            if (isset($request['product_name'])) {
                foreach ($request['product_name'] as $key => $value) {
                    $products[] = [
                        'product_name' => $value,
                        'article' => $request['article'][$key],
                        'model' => $request['model'][$key],
                        'quantity' => $request['quantity'][$key],
                    ];
                }
            }
            $ticket->products()->createMany($products);

            DB::commit();
            return $ticket;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function updateTicket(array $request, $model): bool
    {
        DB::beginTransaction();
        try {
            $ticket = [
                'user_id' =>$request['user_id'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'contract_id' => $request['contract_id'],
                'additional_phone' => $request['additional_phone'],
                'client_name' => $request['client_name'],
                'comment' => $request['comment'],
                'status' => Ticket::STATUS_OPEN,
                'type' => $request['type'],
                'car_id' => $request['car_id'],
            ];
            $model->update($ticket);

            $products = [];
            if (isset($request['product_name'])) {
                foreach ($request['product_name'] as $key => $value) {
                    $products[] = [
                        'product_name' => $value,
                        'article' => $request['article'][$key],
                        'model' => $request['model'][$key],
                        'quantity' => $request['quantity'][$key],
                    ];
                }
            }
            $model->products()->delete();
            $model->products()->createMany($products);

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}
