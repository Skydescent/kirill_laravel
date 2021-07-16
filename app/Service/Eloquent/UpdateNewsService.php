<?php

namespace App\Service\Eloquent;

use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Service\News\UpdateNewsServiceContract;

class UpdateNewsService implements UpdateNewsServiceContract
{
    private NewsRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(NewsRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function update(array $attributes, array $identifier)
    {
        $attributes['published'] = $attributes['published'] ?? 0;

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $news = $this->repository->update($attributes, $identifier);

        if (!is_null($tags)) $this->syncTagsService->syncTags($tags, $news);

    }

}