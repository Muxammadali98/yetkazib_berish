<?php

namespace App\Interfaces\Services;

interface UserServiceInterface
{
    public function create($request);
    public function update($request, $model);
    public function editStatus($model);
}
