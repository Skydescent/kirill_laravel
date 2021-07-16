<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Repository\TaskRepositoryContract;
use App\Contracts\Service\Task\UpdateTaskServiceContract;

class UpdateTaskService implements UpdateTaskServiceContract
{
    private TaskRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(TaskRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function update(array $attributes, string $id, string $userId)
    {
        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $task = $this->repository->update($attributes, $id, $userId);

        if(!is_null($tags) && $tags !== '') $this->syncTagsService->syncTags($tags, $task);
    }

}