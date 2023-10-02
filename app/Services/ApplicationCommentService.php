<?php

namespace App\Services;

use App\Interfaces\Services\ApplicationCommentServiceInterface;
use App\Models\ApplicationComment;

class ApplicationCommentService implements ApplicationCommentServiceInterface
{
    public function create(array $data): void
    {
        ApplicationComment::create($data);
    }
}
