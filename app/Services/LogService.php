<?php

namespace App\Services;

class LogService implements \App\Interfaces\Services\LogServiceInterface
{
    public static function setLog($application_id, $user_id, $status)
    {
        $log = new \App\Models\Log();
        $log->application_id = $application_id;
        $log->user_id = $user_id;
        $log->status = $status;
        $log->save();
    }
}
