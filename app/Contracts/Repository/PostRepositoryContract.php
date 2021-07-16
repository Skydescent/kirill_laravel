<?php

namespace App\Contracts\Repository;

use Illuminate\Database\Eloquent\Model;

interface PostRepositoryContract
{
    public function getPosts(int $postsCount, string $currentPage, int $userId);

    public function getAdminPosts(int $postsCount, string $currentPage);

    public function find($slug): Model;

    public function store(array $attributes) : Model;

    public function update(array $attributes, array $identifier) : Model;

    public function delete($slug) : Model;

    public function addComment(array $attributes, string $postId );
}