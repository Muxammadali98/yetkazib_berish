<?php

namespace App\Interfaces\Services;

interface RegionServiceInterface
{
    public function createRegion($request);
    public function updateRegion($request, $model);
    public function editStatus($model);
}
