<?php

namespace App\Interfaces\Services;

use App\Models\SupplierAssignment;

interface SupplierAssignmentServiceInterface
{
    public function store(array $data);
    public function update(array $data, SupplierAssignment $supplierAssignment);
    public function destroy(SupplierAssignment $supplierAssignment);
    public function restore(SupplierAssignment $supplierAssignment);
}
