<?php

namespace App\Contracts\Service\Task;

interface UpdateTaskServiceContract
{
    public function update(array $attributes, string $id, string $userId);
}