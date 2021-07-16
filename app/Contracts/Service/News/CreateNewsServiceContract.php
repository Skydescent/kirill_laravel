<?php

namespace App\Contracts\Service\News;

interface CreateNewsServiceContract
{
    public function create(array $attributes);
}