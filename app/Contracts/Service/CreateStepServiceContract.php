<?php

namespace App\Contracts\Service;

use App\Contracts\Repository\RepositoryStepableContract;

interface CreateStepServiceContract
{
    public function create(
        array $attributes,
        string $stepableId,
        RepositoryStepableContract $stepableRepository
    );
}