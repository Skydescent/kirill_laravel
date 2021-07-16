<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Post $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return $post->owner_id == $user->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Post $post
     * @return bool
     */
    public function view(?User $user, Post $post)
    {
        return $post->owner_id == optional($user)->id || $post->published === 1;
    }
}
