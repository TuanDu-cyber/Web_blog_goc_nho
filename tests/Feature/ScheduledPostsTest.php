<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduledPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_publishes_due_scheduled_posts(): void
    {
        $due = Post::factory()->scheduled(now()->subMinute())->create();

        $this->artisan('posts:publish')->assertSuccessful();

        $this->assertSame(Post::STATUS_PUBLISHED, $due->fresh()->status);
    }

    public function test_command_leaves_future_posts_and_drafts_alone(): void
    {
        $future = Post::factory()->scheduled(now()->addDay())->create();
        $draft = Post::factory()->draft()->create();

        $this->artisan('posts:publish')->assertSuccessful();

        $this->assertSame(Post::STATUS_SCHEDULED, $future->fresh()->status);
        $this->assertSame(Post::STATUS_DRAFT, $draft->fresh()->status);
    }

    public function test_command_is_registered_in_the_scheduler(): void
    {
        $this->artisan('schedule:list')->expectsOutputToContain('posts:publish')->assertSuccessful();
    }
}
