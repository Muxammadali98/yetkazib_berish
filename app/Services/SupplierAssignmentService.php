<?php

namespace App\Services;

use App\Models\SupplierAssignment;

class SupplierAssignmentService implements \App\Interfaces\Services\SupplierAssignmentServiceInterface
{
    public function store(array $data)
    {
        return SupplierAssignment::create($data);
    }

    public function update(array $data, SupplierAssignment $supplierAssignment)
    {
        return $supplierAssignment->update($data);
    }

    public function destroy(SupplierAssignment $supplierAssignment)
    {
        return $supplierAssignment->delete();
    }

    public function restore(SupplierAssignment $supplierAssignment)
    {
        return $supplierAssignment->restore();
    }
}
