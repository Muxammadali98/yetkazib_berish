<?php

namespace App\Repositories\Api;

use App\Models\SupplierAssignment;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class ProfileRepository implements \App\Interfaces\Api\Repositories\ProfileRepositoryInterface
{
    public function getUserData()
    {
        $current_date = Carbon::now()->format('Y-m-d');
        return User::select(['id','name', 'photo', 'phone_number', 'email', 'address'])
            ->withCount(['tickets as today_ticket_count' => function ($query) use ($current_date) {
                $query->where('status', Ticket::STATUS_CLOSED)
                    ->where('updated_at', '>=', $current_date . ' 00:00:00')
                    ->where('updated_at', '<=', $current_date . ' 23:59:59');
            }])
            ->withCount(['tickets as this_month_ticket_count' => function ($query) use ($current_date) {
                $query->where('status', Ticket::STATUS_CLOSED)
                    ->where('updated_at', '>=', Carbon::now()->startOfMonth());
            }])
            ->withCount(['supplierAssignments as today_assignment_count' => function ($query) use ($current_date) {
                $query->where('status', SupplierAssignment::STATUS_DONE)
                    ->where('updated_at', '>=', $current_date . ' 00:00:00')
                    ->where('updated_at', '<=', $current_date . ' 23:59:59');
            }])
            ->withCount(['supplierAssignments as this_month_assignment_count' => function ($query) use ($current_date) {
                $query->where('status', SupplierAssignment::STATUS_DONE)
                    ->where('updated_at', '>=', Carbon::now()->startOfMonth());
            }])
            ->with(['regions:id,name_' . app()->getLocale() . ' as name'])
            ->where('id', auth()->user()->id)->first();
    }
}
