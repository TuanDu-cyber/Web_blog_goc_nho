<?php

namespace Tests\Feature;

use App\Mail\NewCommentMail;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_comment_and_author_is_emailed_via_queue(): void
    {
        Mail::fake();
        $post = Post::factory()->create();
        $commenter = User::factory()->create();

        $this->actingAs($commenter)
            ->post(route('comment.store', $post), ['content' => 'Bài viết hay quá!'])
            ->assertRedirect(route('article.show', $post) . '#comments');

        $this->assertDatabaseHas('comments', ['post_id' => $post->id, 'user_id' => $commenter->id]);
        Mail::assertQueued(NewCommentMail::class, fn($mail) => $mail->hasTo($post->user->email));
    }

    public function test_author_commenting_on_own_post_sends_no_email(): void
    {
        Mail::fake();
        $post = Post::factory()->create();

        $this->actingAs($post->user)->post(route('comment.store', $post), ['content' => 'Tự bình luận']);

        $this->assertDatabaseCount('comments', 1);
        Mail::assertNothingQueued();
    }

    public function test_guest_cannot_comment(): void
    {
        $post = Post::factory()->create();

        $this->post(route('comment.store', $post), ['content' => 'Khách bình luận'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_empty_or_too_short_comment_is_rejected(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('comment.store', $post), ['content' => 'a'])
            ->assertSessionHasErrors('content');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_cannot_comment_on_a_draft_post(): void
    {
        $draft = Post::factory()->draft()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('comment.store', $draft), ['content' => 'Bình luận nháp'])
            ->assertNotFound();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_commenter_post_author_and_admin_can_delete_comment(): void
    {
        $post = Post::factory()->create();
        $commenter = User::factory()->create();
        $admin = User::factory()->admin()->create();

        foreach ([$commenter, $post->user, $admin] as $user) {
            $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $commenter->id]);

            $this->actingAs($user)->delete(route('comment.destroy', $comment))->assertRedirect();

            $this->assertModelMissing($comment);
        }
    }

    public function test_unrelated_member_cannot_delete_comment(): void
    {
        $comment = Comment::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($stranger)->delete(route('comment.destroy', $comment))->assertForbidden();

        $this->assertModelExists($comment);
    }
}
