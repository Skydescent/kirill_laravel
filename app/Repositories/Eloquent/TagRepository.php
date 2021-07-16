<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\TagRepositoryContract;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryContract
{

    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }


    public function tagsCloud(\Closure $filter, string|null $userId, array $tagCloudRelations = [])
    {
        $getTagsCloudCallback = function () use ($filter) {
            return Tag::tagsCloud($filter);
        };

        $cacheKey = 'tags|tags_cloud'. ($userId ? '|user=' . $userId : '');

        $tags = ['tags_collection'];
        if(count($tagCloudRelations) !== 0) {
            foreach ($tagCloudRelations as $relation) {
                $tags[] = $relation . '_collection';
            }
        }

        return $this->cacheService->cache($tags, $cacheKey, 600, $getTagsCloudCallback);

    }

    public function find($name): Model
    {
        $getPostCallback = function () use ($name) {
            return Tag::with(['posts', 'news'])->firstWhere(['name' => $name]);
        };

        $cacheKey = 'tags|tag=' . $name;
        $tags = ['posts_collection', 'news_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostCallback);
    }

    /**
     * @param Collection|array $tagsToAttach
     * @return array
     */
    public function attachTags(Collection|array $tagsToAttach) : array
    {
        $addedIds = [];

        foreach ($tagsToAttach as $name) {
            $getTagCallback = function () use ($name) {
                return Tag::firstWhere(['name' => $name]);
            };

            $cacheKey = 'tags|tag=' . $name;
            $tags = ['tags'];

            $tag = $this->cacheService->cache($tags, $cacheKey, 600, $getTagCallback);
            $tag = $tag ?? Tag::create(['name' => $name]);
            $addedIds[] = $tag->id;
        }

        return $addedIds;
    }

    /**
     * @param Taggable $model
     * @param array $syncTagsIds
     */
    public function syncTags(Taggable $model, array $syncTagsIds)
    {
        $model->tags()->sync($syncTagsIds);
    }
}