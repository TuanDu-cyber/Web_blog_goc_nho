<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    public function test_status_label_for_each_status(): void
    {
        $draft = new Post(['status' => Post::STATUS_DRAFT]);
        $published = new Post(['status' => Post::STATUS_PUBLISHED]);
        $scheduled = new Post(['status' => Post::STATUS_SCHEDULED, 'scheduled_at' => '2026-11-20 09:30:00']);

        $this->assertSame('Nháp', $draft->statusLabel());
        $this->assertSame('Đã đăng', $published->statusLabel());
        $this->assertSame('Hẹn giờ: 20/11/2026 09:30', $scheduled->statusLabel());
    }

    public function test_post_url_uses_slug_as_route_key(): void
    {
        $this->assertSame('slug', (new Post())->getRouteKeyName());
    }
}
