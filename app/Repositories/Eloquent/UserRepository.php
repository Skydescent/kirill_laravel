<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\UserRepositoryContract;
use App\Models\User;

class UserRepository implements UserRepositoryContract
{
    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function find($id) : ?User
    {
        $getUserCallback = function () use ($id) {
            return User::find($id);
        };

        return $this
            ->cacheService
            ->cache(
                ['users_collection'],
                'users|id=' . $id,
                600,
                $getUserCallback
            );
    }
}