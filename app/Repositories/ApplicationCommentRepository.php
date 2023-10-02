<?php

namespace App\Repositories;

use App\Models\ApplicationComment;

class ApplicationCommentRepository implements \App\Interfaces\Repositories\ApplicationCommentRepositoryInterface
{

    public function getCommentsByApplication(int $application_id)
    {
        return ApplicationComment::where('application_id', $application_id)->with(['user:id,name'])->get();
    }
}
