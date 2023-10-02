<?php

namespace App\Services\Api;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketService implements \App\Interfaces\Api\Services\TicketServiceInterface
{
    public function setProgress(int $id): bool
    {
        $ticket = Ticket::find($id);
        if (!$ticket) return false;
        $ticket->status = Ticket::STATUS_IN_PROGRESS;
        return $ticket->save();
    }

    public function done(array $request): bool
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::find($request['id']);
            if (!$ticket) return false;
            $ticket->status = Ticket::STATUS_CLOSED;
            $ticket->save();
            $actions = [
                'ticket_id' => $request['id'],
                'comment' => $request['comment'],
                'lat' => $request['lat'],
                'lng' => $request['lng'],
            ];
            $ticket->supplierAction()->create($actions);
            $files = [];
            foreach ($request['files'] as $file) {
                $files[] = [
                    'ticket_id' => $request['id'],
                    'supplier_action_id' => $ticket->supplierAction->id,
                    'file' => $this->uploadFile($file)
                ];
            }
            $ticket->supplierFiles()->createMany($files);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function doneByReturn(array $request): bool
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::find($request['id']);
            if (!$ticket) return false;
            $ticket->status = Ticket::STATUS_CLOSED;
            $ticket->save();
            $actions = [
                'ticket_id' => $request['id'],
                'comment' => $request['comment'] ?? null,
            ];
            $ticket->supplierAction()->create($actions);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function uploadFile(array $request): string
    {
        $file = $request['file'];
        $type = explode(';', $file)[0];
        $img = preg_replace('/^data:image\/\w+;base64,/', '', $file);
        $type = explode('/', $type)[1];
        $file_name = time() . '.' . $type;
        $file = base64_decode($img);
        file_put_contents(public_path('uploads/images/' . $file_name), $file);
        return $file_name;
    }
}
