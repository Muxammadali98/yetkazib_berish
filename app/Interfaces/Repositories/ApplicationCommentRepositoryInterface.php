<?php

namespace App\Interfaces\Repositories;

interface ApplicationCommentRepositoryInterface
{
    public function getCommentsByApplication(int $application_id);
}
