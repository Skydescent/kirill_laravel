<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\Task\CreateTaskServiceContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Repository\TaskRepositoryContract;

class CreateTaskService implements CreateTaskServiceContract
{
    private TaskRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(TaskRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function create(array $attributes, string $ownerId)
    {
        $attributes['owner_id'] = $ownerId;

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $task = $this->repository->store($attributes, $ownerId);

        if (!is_null($tags)) $this->syncTagsService->syncTags($tags,$task);
    }
}