<?php

namespace App\Interfaces\Services;

interface GoogleMapServiceInterface
{
    public static function getAddress($lat, $lng);
}
