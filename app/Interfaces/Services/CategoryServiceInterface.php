<?php

namespace App\Interfaces\Services;

interface CategoryServiceInterface
{
    public function createCategory($request);
    public function uploadPDF($request);
    public function setStep($request);
    public function updateCategory($request, $model);
    public function unlinkPDF($model);
    public function softDeleteOrRestoreCategory($model);
}
