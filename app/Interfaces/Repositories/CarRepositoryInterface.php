<?php

namespace App\Interfaces\Repositories;

interface CarRepositoryInterface
{
    public function all();
    public function getCarByUser($user_id);
}
