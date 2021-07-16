<?php

namespace App\Contracts\Repository;

use App\Models\User;

interface UserRepositoryContract
{
    public function find($id) : ?User;
}