<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\StatisticsRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StatisticsController extends Controller
{
    public function index(StatisticsRepositoryContract $statisticsRepository): Factory|View|Application
    {

        $statistics = $statisticsRepository->getStatistics();
        return view('statistics.index', compact( 'statistics'));
    }
}
