<?php

namespace App\Repositories\Api;

use App\Models\SupplierAssignment;
use Carbon\Carbon;

class SupplierAssignmentRepository implements \App\Interfaces\Api\Repositories\SupplierAssignmentRepositoryInterface
{
    public function getTacks($status)
    {
        return SupplierAssignment::select(['id', 'car_id', 'title', 'description', 'phone', 'additional_phone', 'address', 'created_at'])
            ->where('status', $status)
            ->where('user_id', auth()->user()->id)
            ->with(['car:id,name,color,number'])
            ->paginate(SupplierAssignment::PAGE_SIZE);
    }

    public function getDoneTacks($from, $to)
    {
        $from = Carbon::parse($from)->format('Y-m-d') . ' 00:00:00';
        $to = Carbon::parse($to)->format('Y-m-d') . ' 23:59:59';
        return SupplierAssignment::select(['id', 'car_id', 'title', 'description', 'phone', 'additional_phone', 'address', 'created_at'])
            ->where('status', SupplierAssignment::STATUS_DONE)
            ->where('user_id', auth()->user()->id)
            ->with(['car:id,name,color,number'])
            ->whereBetween('created_at', [$from, $to])
            ->paginate(SupplierAssignment::PAGE_SIZE);
    }
}
