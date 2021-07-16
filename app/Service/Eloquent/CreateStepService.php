<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\CreateStepServiceContract;
use App\Contracts\Repository\RepositoryStepableContract;

class CreateStepService implements CreateStepServiceContract
{
    public function create(
        array $attributes,
        string $stepableId,
        RepositoryStepableContract $stepableRepository
    )
    {
        $attributes['completed'] = isset($attributes['completed']) && $attributes['completed'] === 'on';

        $stepableRepository->addStep($attributes, $stepableId);
    }

}