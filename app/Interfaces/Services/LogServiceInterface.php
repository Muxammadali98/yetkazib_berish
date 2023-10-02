<?php

namespace App\Interfaces\Services;

interface LogServiceInterface
{
    public static function setLog($application_id, $user_id, $status);
}
