<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Service\Post\CreatePostServiceContract;
use App\Contracts\Service\Post\DestroyPostServiceContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Contracts\Service\Post\UpdatePostServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','update']);
    }

    /**
     * @param PostRepositoryContract $repository
     * @return Factory|View|Application
     */
    public function index(PostRepositoryContract $repository): Factory|View|Application
    {
        $currentPage = request()->get('page',1);
        $user = cachedUser();
        $posts = $repository->getPosts(10,$currentPage,$user->id);

        return view('posts.index', compact( 'posts', 'user'));
    }

    /**
     * @param PostRepositoryContract $repository
     * @param $slug
     * @return Factory|View|Application
     */
    public function show(PostRepositoryContract $repository, $slug): Factory|View|Application
    {
        $post = $repository->find($slug);

        return view('posts.show', compact('post'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return view('posts.create');
    }

    /**
     * @param PostStoreAndUpdateRequest $request
     * @param CreatePostServiceContract $createPostService
     * @return RedirectResponse
     */
    public function store(
        PostStoreAndUpdateRequest $request,
        CreatePostServiceContract $createPostService
    ): RedirectResponse
    {
        $createPostService->create($request->validated(), getUserId());
        flash('Сообщение успешно добавлено!');
        return redirect()->route('posts.index');
    }

    /**
     * @param PostRepositoryContract $repository
     * @param $slug
     * @return Factory|View|Application
     * @throws AuthorizationException
     */
    public function edit(
        PostRepositoryContract $repository,
        $slug
    ): Factory|View|Application
    {
        $post = $repository->find($slug);
        $this->authorize('update', $post);
        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(
        PostStoreAndUpdateRequest $request,
        PostRepositoryContract $repository,
        UpdatePostServiceContract $updatePostService,
        $slug
    ): RedirectResponse
    {
        $post = $repository->find($slug);
        $this->authorize('update', $post);
        $updatePostService->update($request->validated(), ['slug' => $slug]);

        flash('Статья успешно обновлено!');
        return redirect()->route('posts.index');
    }

    /**
     * @param PostRepositoryContract $repository
     * @param DestroyPostServiceContract $destroyPostService
     * @param $slug
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(
        PostRepositoryContract $repository,
        DestroyPostServiceContract $destroyPostService,
        $slug
    ): RedirectResponse
    {
        $post = $repository->find($slug);
        $this->authorize('update', $post);

        $destroyPostService->delete($slug);

        flash('Статья удалена!', 'warning');
        return redirect()->route('posts.index');
    }
}
