<?php

namespace App\Interfaces\Repositories;

interface UserRepositoryInterface
{
    public function getAllExceptAdmin();
    public function getAllRoleExceptAdmin();
    public function getUserRole($model);
    public function getRegionsId($model);
    public function getSuppliersForSelect();
    public function getActiveOrderList();
}
