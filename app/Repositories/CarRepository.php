<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CarRepositoryInterface;
use App\Models\Car;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class CarRepository implements CarRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Car::with('region:id,name_uz', 'users')->paginate(Car::PAGE_SIZE);
    }

    public function getCarByUser($user_id)
    {
        return User::find($user_id)->cars->where('status', Car::STATUS_ACTIVE)->pluck('name', 'id');
    }
}
