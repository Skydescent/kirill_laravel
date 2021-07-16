<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\RepositoryStepableContract;
use App\Contracts\Repository\TaskRepositoryContract;
use App\Models\Taggable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TaskRepository implements TaskRepositoryContract, RepositoryStepableContract
{
    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getTasks(User $user)
    {
        $getTasksCallback = function () use ($user) {
            return $user->tasks()->with('tags')->latest()->get();
        };

        $cacheKey = 'tasks|user=' . $user->id;
        $tags = ['tasks'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getTasksCallback);

    }

    public function find($id): Model
    {
        $getPostCallback = function () use ($id) {
            return Task::with('steps')->find($id);
        };

        $cacheKey = 'tasks|task=' . $id;
        $tags = ['tasks'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostCallback);
    }

    public function store(array $attributes, string $userId) : Model
    {
        $this->cacheService->forget(['tasks'], 'tasks|user=' . $userId);

        return Task::create($attributes);
    }

    public function update(array $attributes, string $id, string $userId): Model
    {
        $this->cacheService->forget(['tasks'], 'tasks|task=' . $id);
        $this->cacheService->forget(['tasks'], 'tasks|user=' . $userId);

        $task = Task::find($id);
        $task->update($attributes);

        return $task;
    }

    public function delete(string $id, string $userId)
    {
        $post = Task::find($id);
        $post->delete();
        $this->cacheService->forget(['tasks'], 'tasks|task=' . $id);
        $this->cacheService->forget(['tasks'], 'tasks|user=' . $userId);
    }


    /**
     * @param Taggable $model
     * @param array $syncTagsIds
     */
    public function syncTags(Taggable $model, array $syncTagsIds)
    {
        $model->tags()->sync($syncTagsIds);
        $id = $model->id;
    }

    /**
     * @param array $attributes
     * @param string $identifier
     */
    public function addStep(array $attributes, string $stepableIdentifier)
    {
        $task = $this->find($stepableIdentifier);
        $task->addStep($attributes);
        $this->cacheService->forget(['tasks'], 'tasks|task=' . $stepableIdentifier);

    }

    public function completeStep(string $stepId, string $stepableId, string $stepMethod ='complete')
    {
        $task = $this->find($stepableId);
        $step = $task->steps->firstWhere('id', $stepId);
        $this->cacheService->forget(['tasks'], 'tasks|task=' . $stepableId);

        $step->$stepMethod();
    }

    public function incompleteStep( string $stepId, string $stepableId)
    {
        $this->completeStep($stepId, $stepableId, 'incomplete');
    }
}