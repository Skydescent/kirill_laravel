<?php

namespace App\Contracts\Repository;

use App\Models\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface TagRepositoryContract
{
    public function attachTags(Collection|array $tagsToAttach) : array;

    public function tagsCloud(\Closure $filter, string|null $userId, array $tagCloudRelations = []);

    public function syncTags(Taggable $model, array $syncTagsIds);

    public function find($name): Model;
}