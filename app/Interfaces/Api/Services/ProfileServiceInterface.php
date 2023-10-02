<?php

namespace App\Interfaces\Api\Services;

interface ProfileServiceInterface
{

    public function profileUpdate($request);

    public function passwordUpdate(\Illuminate\Http\Request $request);

    public function avatarUpdate(\Illuminate\Http\Request $request);
}
