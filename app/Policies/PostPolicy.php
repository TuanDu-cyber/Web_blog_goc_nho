<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    private function isOwner(User $user, Post $post): bool
    {
        return (int) $user->id === (int) $post->user_id;
    }

    public function update(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }

    public function restore(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }
}
