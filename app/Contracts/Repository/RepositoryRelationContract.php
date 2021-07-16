<?php

namespace App\Contracts\Repository;

interface RepositoryRelationContract
{
    public function getRelation(string $relation, array $identifier);
}