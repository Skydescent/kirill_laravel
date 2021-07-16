<?php

namespace App\Contracts\Service\Post;

interface CreatePostServiceContract
{
    public function create(array $attributes, string $ownerId);

}