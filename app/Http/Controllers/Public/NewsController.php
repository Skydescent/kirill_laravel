<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\NewsRepositoryContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class NewsController
{

    /**
     * @param NewsRepositoryContract $repository
     * @return Application|Factory|View
     */
    public function index(NewsRepositoryContract $repository): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $news = $repository->getNews(10,$currentPage);

        return view('news.index', compact( 'news'));
    }


    /**
     * @param NewsRepositoryContract $repository
     * @param $slug
     * @return Application|Factory|View
     */
    public function show(NewsRepositoryContract $repository, $slug): View|Factory|Application
    {
        $news = $repository->find($slug);
        return view('news.show', compact('news'));
    }
}
