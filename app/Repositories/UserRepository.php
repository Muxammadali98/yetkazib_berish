<?php

namespace App\Repositories;

use App\Models\SupplierAssignment;
use App\Models\Ticket;
use App\Models\User;

class UserRepository implements \App\Interfaces\Repositories\UserRepositoryInterface
{

    public function getAllExceptAdmin()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->paginate(User::PAGE_SIZE);
    }

    public function getAllRoleExceptAdmin(): array
    {
        return User::roleAlias();
    }

    public function getUserRole($model)
    {
        return $model->roles->pluck('name')->toArray();
    }

    public function getRegionsId($model)
    {
        return $model->regions()->pluck('regions.id')->toArray();
    }

    public function getSuppliersForSelect()
    {
        return User::role('delivery')->get()->pluck('name', 'id');
    }

    public function getActiveOrderList()
    {
        return User::select(['id', 'name'])
            ->role('delivery')
            ->whereHas('supplierAssignments', function ($query) {
                $query->where('status', '!=', SupplierAssignment::STATUS_DONE)
                    ->where('status', '!=', SupplierAssignment::STATUS_REJECTED);
            })
            ->whereHas('tickets', function ($query) {
                $query->where('status', '!=', Ticket::STATUS_CLOSED)
                    ->where('status', '!=', Ticket::STATUS_CANCELLED);
            })
            ->withCount(['supplierAssignments as assignment_order_count' => function ($query) {
                $query->where('status', '!=', SupplierAssignment::STATUS_DONE)
                    ->where('status', '!=', SupplierAssignment::STATUS_REJECTED);
            }])->withCount(['tickets as ticket_order_count' => function ($query) {
                $query->where('status', '!=', SupplierAssignment::STATUS_DONE)
                    ->where('status', '!=', SupplierAssignment::STATUS_REJECTED);
            }])
            ->orderBy('assignment_order_count', 'asc')
            ->orderBy('ticket_order_count', 'asc')
            ->get();
    }
}
