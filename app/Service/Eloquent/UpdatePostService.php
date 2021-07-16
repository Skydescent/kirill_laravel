<?php

namespace App\Service\Eloquent;

use App\Contracts\Repository\PostRepositoryContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Service\Post\UpdatePostServiceContract;

class UpdatePostService implements UpdatePostServiceContract
{
    private PostRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(PostRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function update(array $attributes, array $identifier)
    {
        $attributes['published'] = $attributes['published'] ?? 0;

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $post = $this->repository->update($attributes, $identifier);

        if (!is_null($tags)) $this->syncTagsService->syncTags($tags, $post);
    }
}