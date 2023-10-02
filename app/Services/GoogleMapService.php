<?php

namespace App\Services;

class GoogleMapService implements \App\Interfaces\Services\GoogleMapServiceInterface
{
    public static function getAddress($lat, $lng)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&key=' . config('app.google_maps_key');
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        $address = '';
        if ($status == "OK") {
            $address = $data->results[0]->formatted_address;
        }
        return $address;
    }
}
