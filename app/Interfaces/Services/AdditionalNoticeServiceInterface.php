<?php

namespace App\Interfaces\Services;

interface AdditionalNoticeServiceInterface
{
    public function create($request);
    public function update($request, $model);
    public function sendNotice($model);
}
