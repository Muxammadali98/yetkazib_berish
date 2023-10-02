<?php

namespace App\Interfaces\Services;

interface CarServiceInterface
{
    public function create($request): bool;
    public function update($request, $model): bool;
    public function hybridSleep($id): bool;
}
