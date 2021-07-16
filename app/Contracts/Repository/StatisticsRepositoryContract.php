<?php

namespace App\Contracts\Repository;

interface StatisticsRepositoryContract
{
    public function getStatistics(): array;
}