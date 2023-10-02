<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardRepository $dashboardRepository)
    {
    }

    public function dashboard()
    {
        return view('dashboard', [
            'activeClientCount' => $this->dashboardRepository->getActiveClientCount(),
            'activeApplicationCount' => $this->dashboardRepository->getActiveApplicationCount(),
            'acceptedApplicationCount' => $this->dashboardRepository->getAcceptedApplicationCount(),
            'rejectedApplicationCount' => $this->dashboardRepository->getRejectedApplicationCount(),
        ]);
    }
}
