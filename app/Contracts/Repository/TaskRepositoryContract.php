<?php

namespace App\Contracts\Repository;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface TaskRepositoryContract
{
    public function getTasks(User $user);

    public function find($id): Model;

    public function store(array $attributes, string $userId) : Model;

    public function update(array $attributes, string $id, string $userId): Model;

    public function delete(string $id, string $userId);

}