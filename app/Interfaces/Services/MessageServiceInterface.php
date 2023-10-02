<?php

namespace App\Interfaces\Services;

interface MessageServiceInterface
{
    public function createMessage($request);
    public function stripTags($text): string;
    public function updateMessage($request, $model): bool;
    public function editStatus($model): bool;
    public function inActiveStatus($type);
    public function clean($text): string;
}
