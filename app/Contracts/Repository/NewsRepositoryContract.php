<?php

namespace App\Contracts\Repository;

use Illuminate\Database\Eloquent\Model;

interface NewsRepositoryContract
{
    public function getNews(int $newsCount, string $currentPage);

    public function getAdminNews(int $newsCount, string $currentPage);

    public function find($slug) : Model;

    public function store(array $attributes) : Model;

    public function update(array $attributes, array $identifier): Model;

    public function addComment(array $attributes, string $newsId );
}