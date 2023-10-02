<?php

namespace App\Interfaces\Services;

interface QuestionServiceInterface
{
    public function createQuestion($request);
    public function updateQuestion($request, $model);
    public function stripTags($text);
    public function updateStep($list);
    public function updateStatus($id);
    public function strReplace($text);
}
