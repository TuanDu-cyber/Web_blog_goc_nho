<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{
    private function makeUser(int $id, string $role = User::ROLE_MEMBER): User
    {
        $user = new User();
        $user->id = $id;
        $user->role = $role;

        return $user;
    }

    // Bình luận của user $commentUserId, dưới bài của user $postAuthorId
    private function makeComment(int $commentUserId, int $postAuthorId): Comment
    {
        $post = new Post();
        $post->user_id = $postAuthorId;

        $comment = new Comment();
        $comment->user_id = $commentUserId;
        $comment->setRelation('post', $post);

        return $comment;
    }

    public function test_commenter_and_post_author_can_delete(): void
    {
        $comment = $this->makeComment(commentUserId: 2, postAuthorId: 3);
        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($this->makeUser(2), $comment));
        $this->assertTrue($policy->delete($this->makeUser(3), $comment));
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $comment = $this->makeComment(commentUserId: 2, postAuthorId: 3);

        $this->assertTrue((new CommentPolicy())->delete($this->makeUser(9, User::ROLE_ADMIN), $comment));
    }

    public function test_unrelated_member_cannot_delete(): void
    {
        $comment = $this->makeComment(commentUserId: 2, postAuthorId: 3);

        $this->assertFalse((new CommentPolicy())->delete($this->makeUser(7), $comment));
    }
}
