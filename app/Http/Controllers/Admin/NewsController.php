<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Service\News\CreateNewsServiceContract;
use App\Contracts\Service\News\DestroyNewsServiceContract;
use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Service\News\UpdateNewsServiceContract;
use App\Http\Requests\NewsStoreAndUpdateRequest;
use App\Models\News;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController
{

    /**
     * Display a listing of the resource.
     *
     * @param NewsRepositoryContract $repository
     * @return View
     */
    public function index(NewsRepositoryContract $repository): View
    {
        $currentPage = request()->get('page',1);
        $news = $repository->getAdminNews(10,$currentPage);

        return view('admin.news.index', compact( 'news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $news = new News();
        return view('admin.news.create', compact('news'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewsStoreAndUpdateRequest $request
     * @param CreateNewsServiceContract $createNewsService
     * @return RedirectResponse
     */
    public function store(
        NewsStoreAndUpdateRequest $request,
        CreateNewsServiceContract $createNewsService
    ) : RedirectResponse
    {
        $createNewsService->create($request->validated());
        return redirect()->route('admin.news.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param NewsRepositoryContract $repository
     * @param $slug
     * @return View
     */
    public function edit(NewsRepositoryContract $repository, $slug) : View
    {
        $news = $repository->find($slug);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NewsStoreAndUpdateRequest $request
     * @param UpdateNewsServiceContract $updateNewsService
     * @param $slug
     * @return RedirectResponse
     */
    public function update(
        NewsStoreAndUpdateRequest $request,
        UpdateNewsServiceContract $updateNewsService,
        $slug
    ): RedirectResponse
    {
        $updateNewsService->update($request->validated(), ['slug' => $slug]);

        flash('Новость успешно обновлена!');

        return redirect()->route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyNewsServiceContract $destroyNewsService
     * @param $slug
     * @return RedirectResponse
     */
    public function destroy(DestroyNewsServiceContract $destroyNewsService, $slug): RedirectResponse
    {
        $destroyNewsService->delete($slug);

        flash('Статья удалена!', 'warning');
        return redirect()->route('admin.news.index');
    }
}
