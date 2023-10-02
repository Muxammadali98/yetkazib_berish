<?php

namespace App\Interfaces\Repositories;

interface RegionRepositoryInterface
{
    public function getRegions();
    public function getAllRegionsForSelect();
    public function getUserIds($ids);
}
