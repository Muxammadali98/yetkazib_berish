<?php

namespace App\Repositories;

use App\Models\Answer;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements \App\Interfaces\Repositories\DashboardRepositoryInterface
{
    public function getActiveClientCount(): int
    {
        return \App\Models\Client::where('status', \App\Models\Client::STATUS_ACTIVE)->count();
    }

    public function getActiveApplicationCount(): int
    {
        return \App\Models\Application::where('status', \App\Models\Application::STATUS_ACTIVE)->count();
    }

    public function getAcceptedApplicationCount(): int
    {
        return Answer::whereIn('id', function ($query) {
                $query->select(DB::raw('max(id)'))
                    ->from('answers')
                    ->where('status', \App\Models\Answer::STATUS_MANAGER_ACCEPTED)
                    ->orWhere('status', \App\Models\Answer::STATUS_CONTRACT_ACCEPTED)
                    ->groupBy('application_id');
            })
            ->count();
    }

    public function getRejectedApplicationCount(): int
    {
        return Answer::whereIn('id', function ($query) {
                $query->select(DB::raw('max(id)'))
                    ->from('answers')
                    ->where('status', \App\Models\Answer::STATUS_MANAGER_REJECTED)
                    ->orWhere('status', \App\Models\Answer::STATUS_CONTRACT_REJECTED)
                    ->groupBy('application_id');
            })
            ->count();
    }
}
