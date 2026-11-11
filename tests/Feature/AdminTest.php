<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_member_gets_403_on_admin_area(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($member)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($member)->post(route('admin.categories.store'), ['name' => 'Hack'])->assertForbidden();
        $this->assertDatabaseMissing('categories', ['name' => 'Hack']);
    }

    public function test_admin_sees_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        $popular = Post::factory()->create(['user_id' => $member->id]);
        Post::factory()->create(['user_id' => $member->id]);
        Comment::factory()->count(3)->create(['post_id' => $popular->id, 'user_id' => $member->id]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

        $this->assertSame(1, $response->viewData('memberCount')); // admin không tính là thành viên
        $this->assertSame(2, $response->viewData('postCount'));
        $this->assertSame(3, $response->viewData('commentCount'));
        $this->assertSame($popular->id, $response->viewData('topPosts')->first()->id);
    }

    public function test_admin_can_create_and_update_category(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Du lịch'])
            ->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Du lịch', 'slug' => 'du-lich']);

        $category = Category::where('slug', 'du-lich')->firstOrFail();
        $this->actingAs($admin)->put(route('admin.categories.update', $category), ['name' => 'Phượt'])
            ->assertRedirect(route('admin.categories.index'));
        $this->assertSame('phuot', $category->fresh()->slug);
    }

    public function test_category_name_must_be_valid_and_unique(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->create(['name' => 'Du lịch xa', 'slug' => 'du-lich-xa']);
        $before = Category::count();

        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Du lịch xa'])
            ->assertSessionHasErrors('slug');
        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertSame($before, Category::count()); // không có danh mục nào được thêm
    }

    public function test_category_with_posts_cannot_be_deleted_but_empty_one_can(): void
    {
        $admin = User::factory()->admin()->create();
        $used = Category::factory()->create();
        Post::factory()->create(['category_id' => $used->id]);
        $empty = Category::factory()->create();

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $used))
            ->assertSessionHas('error');
        $this->assertModelExists($used);

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $empty))
            ->assertRedirect(route('admin.categories.index'));
        $this->assertModelMissing($empty);
    }

    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        $this->actingAs($admin)->delete(route('admin.posts.destroy', $post))->assertRedirect();

        $this->assertModelMissing($post);
    }

    public function test_member_cannot_use_admin_post_deletion(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->user)->delete(route('admin.posts.destroy', $post))->assertForbidden();

        $this->assertModelExists($post);
    }

    public function test_admin_can_list_and_delete_comments(): void
    {
        $admin = User::factory()->admin()->create();
        $comment = Comment::factory()->create(['content' => 'Bình luận vi phạm']);

        $this->actingAs($admin)->get(route('admin.comments.index'))->assertOk()->assertSee('Bình luận vi phạm');
        $this->actingAs($admin)->delete(route('admin.comments.destroy', $comment))->assertRedirect();

        $this->assertModelMissing($comment);
    }
}
