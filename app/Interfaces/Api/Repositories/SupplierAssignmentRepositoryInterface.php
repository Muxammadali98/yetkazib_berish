<?php

namespace App\Interfaces\Api\Repositories;

interface SupplierAssignmentRepositoryInterface
{
    public function getTacks($status);

    public function getDoneTacks($from, $to);
}
