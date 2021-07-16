<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Repository\TagRepositoryContract;
use App\Models\Taggable;

class SyncTagsService implements SyncTagsServiceContract
{
    private TagRepositoryContract $tagRepository;

    public function __construct(TagRepositoryContract $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function syncTags(
        string $requestTags,
        Taggable $model
    )
    {
        $syncIds = [];
        $tags = collect($this->cleanTagsString($requestTags));

        if ($model->tags()->get()->isNotEmpty()) {
            $itemTags = $model->tags()->get()->keyBy('name');
            $tags = $tags->keyBy(function ($item) { return $item; });
            $syncIds = $itemTags->intersectByKeys($tags)->pluck('id')->toArray();
            $tagsToAttach = $tags->diffKeys($itemTags);
        } else {
            $tagsToAttach = $tags;
        }

        $syncIds = array_merge($syncIds,  $this->tagRepository->attachTags($tagsToAttach));

        $this->tagRepository->syncTags($model, $syncIds);

    }

    private function cleanTagsString(string $tags) : array
    {
        return preg_split("/(^\s*)|(\s*,\s*)/", $tags, 0,PREG_SPLIT_NO_EMPTY);
    }

}