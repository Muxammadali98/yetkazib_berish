<?php

namespace App\Interfaces\Repositories;

interface SupplierAssignmentRepositoryInterface
{
    public function getSupplierAssignments($status);
    public function getDelivery();
    public function getTrash();
    public function getSupplierAssignment($id);
}
