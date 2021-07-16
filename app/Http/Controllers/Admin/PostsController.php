<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Service\Post\UpdatePostServiceContract;
use App\Contracts\Service\Post\DestroyPostServiceContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Http\Requests\PostStoreAndUpdateRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostsController
{

    /**
     * @param PostRepositoryContract $repository
     * @return Application|Factory|View
     */
    public function index(PostRepositoryContract $repository): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $posts = $repository->getAdminPosts(20, $currentPage);
        return view('admin.posts.index', compact( 'posts'));
    }

    /**
     * @param PostRepositoryContract $repository
     * @param $slug
     * @return Application|Factory|View
     */
    public function edit(PostRepositoryContract $repository, $slug): View|Factory|Application
    {
        $post = $repository->find($slug);
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @param PostStoreAndUpdateRequest $request
     * @param UpdatePostServiceContract $updatePostService
     * @param $slug
     * @return RedirectResponse
     */
    public function update(
        PostStoreAndUpdateRequest $request,
        UpdatePostServiceContract $updatePostService,
        $slug): RedirectResponse
    {
        $updatePostService->update($request->validated(), ['slug' => $slug]);
        flash('Сообщение успешно обновлено!');
        return redirect()->route('admin.posts.index');
    }


    /**
     * @param DestroyPostServiceContract $destroyPostService
     * @param string $slug
     * @return RedirectResponse
     */
    public function destroy(
        DestroyPostServiceContract $destroyPostService,
        string $slug
    ): RedirectResponse
    {
        $destroyPostService->delete($slug);

        flash('Статья удалена!', 'warning');
        return redirect()->route('admin.posts.index');
    }
}
