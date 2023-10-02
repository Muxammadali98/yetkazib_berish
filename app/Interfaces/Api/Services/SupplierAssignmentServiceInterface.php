<?php

namespace App\Interfaces\Api\Services;

interface SupplierAssignmentServiceInterface
{

    public function approve(int $id): bool;

    public function done(array $request): bool;
}
