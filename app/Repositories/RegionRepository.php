<?php

namespace App\Repositories;

use App\Models\Region;
use App\Models\User;

class RegionRepository implements \App\Interfaces\Repositories\RegionRepositoryInterface
{
    public function getRegions()
    {
        return Region::paginate(Region::PAGE_SIZE);
    }

    public function getAllRegionsForSelect(): \Illuminate\Support\Collection
    {
        return Region::all()->pluck('name_uz', 'id');
    }

    public function getUserIds($ids): \Illuminate\Database\Eloquent\Collection|array
    {
        return Region::with(['users' => function($query) {
            $query->where('users.status', User::STATUS_ACTIVE);
        }])->select('id')->whereIn('id', $ids)->get();
    }
}
