<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\News\CreateNewsServiceContract;
use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;

class CreateNewsService implements CreateNewsServiceContract
{
    private NewsRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(NewsRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function create(array $attributes)
    {
        $attributes['published'] = $attributes['published'] ?? 0;

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $news = $this->repository->store($attributes);

        if (!is_null($tags)) $this->syncTagsService->syncTags($tags,$news);

    }


}