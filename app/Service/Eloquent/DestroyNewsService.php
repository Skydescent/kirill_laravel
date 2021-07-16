<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\News\DestroyNewsServiceContract;
use App\Contracts\Repository\NewsRepositoryContract;

class DestroyNewsService implements DestroyNewsServiceContract
{
    private NewsRepositoryContract $repository;

    public function __construct(NewsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }


    public function delete($slug)
    {
        $post = $this->repository->delete($slug);
    }
}