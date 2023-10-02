<?php

namespace App\Interfaces\Repositories;

interface DashboardRepositoryInterface
{
    public function getActiveClientCount(): int;
    public function getActiveApplicationCount(): int;
    public function getAcceptedApplicationCount(): int;
    public function getRejectedApplicationCount(): int;
}
