<?php

namespace App\Contracts\Service\News;

interface UpdateNewsServiceContract
{
    public function update(array $attributes, array $identifier);
}