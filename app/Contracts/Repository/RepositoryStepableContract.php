<?php

namespace App\Contracts\Repository;

interface RepositoryStepableContract
{
    public function addStep(array $attributes, string $stepableIdentifier);

    public function completeStep(string $stepId, string $stepableId);

    public function incompleteStep( string $stepId, string $stepableId);

}