<?php

namespace App\Services\Api;

use App\Models\SupplierAssignment;

class SupplierAssignmentService implements \App\Interfaces\Api\Services\SupplierAssignmentServiceInterface
{
    public function approve(int $id): bool
    {
        $tack = SupplierAssignment::find($id);
        if ($tack) {
            $tack->status = SupplierAssignment::STATUS_APPROVED;
            return $tack->save();
        }
        return false;
    }

    public function done(array $request): bool
    {
        $tack = SupplierAssignment::find($request['id']);
        if ($tack) {
            $tack->fill([
                'status' => SupplierAssignment::STATUS_DONE,
                'comment' => $request['comment'] ?? null,
            ]);
            return $tack->save();
        }
        return false;
    }
}
