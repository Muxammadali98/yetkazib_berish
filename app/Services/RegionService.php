<?php

namespace App\Services;

use App\Models\Region;

class RegionService implements \App\Interfaces\Services\RegionServiceInterface
{
    public function createRegion($request)
    {
        return Region::create($request->all());
    }

    public function updateRegion($request, $model)
    {
        return $model->update($request->all());
    }

    public function editStatus($model)
    {
        $status = $model->status == Region::STATUS_ACTIVE ? Region::STATUS_INACTIVE : Region::STATUS_ACTIVE;
        return $model->update(['status' => $status]);
    }
}
