<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Service\Task\CreateTaskServiceContract;
use App\Contracts\Repository\TaskRepositoryContract;
use App\Contracts\Service\Task\UpdateTaskServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreAndUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(TaskRepositoryContract $repository): Factory|View|Application
    {
        $tasks = $repository->getTasks(cachedUser());
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(TaskRepositoryContract $repository, $id): Factory|View|Application
    {
        $task = $repository->find($id);
        $this->authorize('update', $task);
        return view('tasks.show', compact('task'));
    }

    public function create(): Factory|View|Application
    {
        return view('tasks.create');
    }

    public function store(
        TaskStoreAndUpdateRequest $request,
        CreateTaskServiceContract $createTaskService
    ): Redirector|Application|RedirectResponse
    {
        $createTaskService->create($request->validated(), getUserId());
        return redirect('/tasks');
    }


    public function edit(TaskRepositoryContract $repository, $id): Factory|View|Application
    {
        $task = $repository->find($id);
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }


    public function update(
        TaskStoreAndUpdateRequest $request,
        UpdateTaskServiceContract $updateTaskService,
        TaskRepositoryContract $repository,
        $id
    ): Redirector|Application|RedirectResponse
    {
        $task = $repository->find($id);
        $this->authorize('update', $task);
        $updateTaskService->update($request->validated(), $id, getUserId());

        return redirect('/tasks');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(TaskRepositoryContract $repository, $id): Redirector|Application|RedirectResponse
    {
        $task = $repository->find($id);
        $this->authorize('update', $task);

        $repository->delete($id, getUserId());

        return redirect('/tasks');
    }

}
