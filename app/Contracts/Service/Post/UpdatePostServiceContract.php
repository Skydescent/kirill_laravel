<?php

namespace App\Contracts\Service\Post;

interface UpdatePostServiceContract
{
    public function update(array $attributes, array $identifier);
}