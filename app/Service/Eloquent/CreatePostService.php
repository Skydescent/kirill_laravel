<?php

namespace App\Service\Eloquent;

use App\Contracts\Service\Post\CreatePostServiceContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Notifications\PostStatusChanged;
use App\Recipients\AdminRecipient;

class CreatePostService implements CreatePostServiceContract
{
    private PostRepositoryContract $repository;

    private SyncTagsServiceContract $syncTagsService;

    public function __construct(PostRepositoryContract $repository, SyncTagsServiceContract $syncTagsService)
    {
        $this->repository = $repository;
        $this->syncTagsService = $syncTagsService;
    }

    public function create(array $attributes, string $ownerId)
    {
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = $ownerId;

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $post = $this->repository->store($attributes);

        if (!is_null($tags)) $this->syncTagsService->syncTags($tags,$post);

        (new AdminRecipient())->notify(new PostStatusChanged(
            'добавлена статья',
            $post->title,
            route('posts.show', ['post' => $post])
        ));
    }

}