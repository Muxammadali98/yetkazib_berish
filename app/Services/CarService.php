<?php

namespace App\Services;

use App\Interfaces\Services\CarServiceInterface;
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarService implements CarServiceInterface
{
    public function create($request): bool
    {
        DB::beginTransaction();
        try {
            $car = Car::create($request->except('user_id'));
            $car->users()->attach($request->user_id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($request, $model): bool
    {
        DB::beginTransaction();
        try {
            $model->update($request->except('user_id'));
            $model->users()->sync($request->user_id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function hybridSleep($id): bool
    {
        $model = Car::findOrfail($id);
        $status = $model->status === Car::STATUS_ACTIVE ? Car::STATUS_INACTIVE : Car::STATUS_ACTIVE;
        return $model->update(['status' => $status]);
    }
}
