<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\Post\DestroyPostServiceContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Notifications\PostStatusChanged;
use App\Recipients\AdminRecipient;

class DestroyPostService implements DestroyPostServiceContract
{
    private PostRepositoryContract $repository;

    public function __construct(PostRepositoryContract $repository)
    {
        $this->repository = $repository;
    }


    public function delete($slug)
    {
        $post = $this->repository->delete($slug);


        (new AdminRecipient())->notify(new PostStatusChanged(
            'удалена статья',
            $post->title
        ));
    }
}