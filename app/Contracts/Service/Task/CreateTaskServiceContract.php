<?php

namespace App\Contracts\Service\Task;

interface CreateTaskServiceContract
{
    public function create(array $attributes, string $ownerId);
}