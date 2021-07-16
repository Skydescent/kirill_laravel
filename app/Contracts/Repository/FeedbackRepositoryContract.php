<?php

namespace App\Contracts\Repository;

use Illuminate\Database\Eloquent\Model;

interface FeedbackRepositoryContract
{
    public function getFeedbacks();

    public function store(array $attributes) : Model;
}