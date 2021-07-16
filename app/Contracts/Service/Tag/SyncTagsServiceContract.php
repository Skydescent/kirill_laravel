<?php

namespace App\Contracts\Service\Tag;

use App\Models\Taggable;

interface SyncTagsServiceContract
{
    public function syncTags(string $requestTags, Taggable $model);
}