<?php

namespace App\Repositories;

use App\Models\SupplierAssignment;
use App\Models\User;

class SupplierAssignmentRepository implements \App\Interfaces\Repositories\SupplierAssignmentRepositoryInterface
{

    public function getSupplierAssignments($status)
    {
        return SupplierAssignment::with(['user:id,name'])
            ->where('status', $status)
            ->paginate(SupplierAssignment::PAGE_SIZE);
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

    public function getTrash()
    {
        return SupplierAssignment::onlyTrashed()->paginate(SupplierAssignment::PAGE_SIZE);
    }

    public function getSupplierAssignment($id)
    {
        return SupplierAssignment::withTrashed()->where('id', $id)->first();
    }
}
