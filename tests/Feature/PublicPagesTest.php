<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_published_posts_only(): void
    {
        Post::factory()->create(['title' => 'Bài đã đăng']);
        Post::factory()->draft()->create(['title' => 'Bài nháp bí mật']);
        Post::factory()->scheduled()->create(['title' => 'Bài hẹn giờ chưa tới']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Bài đã đăng')
            ->assertDontSee('Bài nháp bí mật')
            ->assertDontSee('Bài hẹn giờ chưa tới');
    }

    public function test_home_orders_newest_first(): void
    {
        Post::factory()->create(['published_at' => now()->subDays(3)]);
        $newest = Post::factory()->create(['published_at' => now()]);

        $posts = $this->get('/')->viewData('posts');

        $this->assertSame($newest->id, $posts->first()->id);
    }

    public function test_home_is_paginated_with_9_posts_per_page(): void
    {
        Post::factory()->count(12)->create();

        $page1 = $this->get('/')->assertOk()->viewData('posts');
        $this->assertCount(9, $page1);
        $this->assertSame(12, $page1->total());

        $page2 = $this->get('/?page=2')->viewData('posts');
        $this->assertCount(3, $page2);
    }

    public function test_search_finds_posts_matching_keyword(): void
    {
        Post::factory()->create(['title' => 'Hướng dẫn Laravel cơ bản']);
        Post::factory()->create(['title' => 'Chuyến đi Sapa']);

        $this->get('/?q=Laravel')
            ->assertOk()
            ->assertSee('Hướng dẫn Laravel cơ bản')
            ->assertDontSee('Chuyến đi Sapa');
    }

    public function test_search_without_match_shows_empty_message(): void
    {
        Post::factory()->create();

        $this->get('/?q=khongcotuanay')
            ->assertOk()
            ->assertSee('Không tìm thấy bài viết phù hợp.');
    }

    public function test_can_filter_by_category(): void
    {
        $travel = Category::factory()->create(['name' => 'Du lịch', 'slug' => 'du-lich']);
        $study = Category::factory()->create(['name' => 'Học tập', 'slug' => 'hoc-tap']);
        Post::factory()->create(['title' => 'Bài về núi', 'category_id' => $travel->id]);
        Post::factory()->create(['title' => 'Bài về thi cử', 'category_id' => $study->id]);

        $this->get('/?category=du-lich')
            ->assertSee('Bài về núi')
            ->assertDontSee('Bài về thi cử');
    }

    public function test_can_filter_by_tag(): void
    {
        $tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tagged = Post::factory()->create(['title' => 'Có gắn tag']);
        $tagged->tags()->attach($tag);
        Post::factory()->create(['title' => 'Không gắn tag']);

        $this->get('/?tag=laravel')
            ->assertSee('Có gắn tag')
            ->assertDontSee('Không gắn tag');
    }

    public function test_article_is_reachable_by_slug(): void
    {
        $post = Post::factory()->create(['title' => 'Bài chi tiết']);

        $this->assertStringContainsString($post->slug, route('article.show', $post));
        $this->get(route('article.show', $post))->assertOk()->assertSee('Bài chi tiết');
    }

    public function test_draft_article_is_hidden_from_guests(): void
    {
        $draft = Post::factory()->draft()->create();

        $this->get(route('article.show', $draft))->assertNotFound();
    }

    public function test_draft_article_is_visible_to_its_author(): void
    {
        $draft = Post::factory()->draft()->create(['title' => 'Nháp của tôi']);

        $this->actingAs($draft->user)->get(route('article.show', $draft))
            ->assertOk()
            ->assertSee('Nháp của tôi');
    }
}
