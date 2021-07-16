<?php

namespace App\Contracts\Service\Tag;

interface TagsCloudServiceContract
{
    public function tagsCloud(string|null $userId = null);
}