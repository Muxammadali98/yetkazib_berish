<?php

namespace App\Interfaces\Services;

use App\Models\Start;

interface StartServiceInterface
{
    public function createStartMessage($request);
    public function updateStartMessage($request, $model);
    public function upload($request);
    public function updateUpload($request, $id);
    public function editStatus($id);
    public function inActiveStatus();
    public function stripTags($text);
    public function strReplace($text);
}
