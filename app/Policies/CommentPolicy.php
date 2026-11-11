<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    // Được xóa: người viết bình luận, tác giả bài viết, hoặc quản trị viên
    public function delete(User $user, Comment $comment): bool
    {
        return $user->isAdmin()
            || (int) $user->id === (int) $comment->user_id
            || (int) $user->id === (int) $comment->post?->user_id;
    }
}
