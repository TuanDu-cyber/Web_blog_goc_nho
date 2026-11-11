<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    // Dữ liệu form hợp lệ (chưa có ảnh)
    private function validData(Category $category, array $override = []): array
    {
        return array_merge([
            'title'       => 'Bài viết thử nghiệm',
            'category_id' => $category->id,
            'content'     => 'Nội dung đủ dài để vượt qua kiểm tra tối thiểu.',
            'status'      => 'published',
        ], $override);
    }

    // Ảnh PNG 1x1 hợp lệ, không cần extension GD
    private function fakeImage(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'anh.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC')
        );
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('post.index'))->assertRedirect(route('login'));
        $this->get(route('post.create'))->assertRedirect(route('login'));
    }

    public function test_member_can_create_a_post_with_tags(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $this->actingAs($user)->post(route('post.store'), $this->validData($category, [
            'tags'       => [$tag->id],
            'image_file' => $this->fakeImage(),
        ]))->assertRedirect(route('post.index'));

        $post = Post::where('title', 'Bài viết thử nghiệm')->firstOrFail();
        $this->assertSame($user->id, $post->user_id);
        $this->assertTrue($post->tags->contains($tag));
        $this->assertNotEmpty($post->slug);

        File::delete(public_path($post->image)); // dọn ảnh test đã upload
    }

    public function test_create_post_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('post.store'), [
            'title'       => '',
            'category_id' => 999,
            'content'     => 'ngắn',
            'status'      => 'khong-hop-le',
        ])->assertSessionHasErrors(['title', 'category_id', 'content', 'image_file', 'status']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_member_can_schedule_a_post_for_the_future(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->post(route('post.store'), $this->validData($category, [
            'status'       => 'scheduled',
            'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'image_file'   => $this->fakeImage(),
        ]))->assertRedirect(route('post.index'));

        $post = Post::where('title', 'Bài viết thử nghiệm')->firstOrFail();
        $this->assertSame(Post::STATUS_SCHEDULED, $post->status);
        $this->assertNotNull($post->scheduled_at);

        File::delete(public_path($post->image));
    }

    public function test_scheduled_post_needs_a_future_time(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->post(route('post.store'), $this->validData($category, [
            'status'       => 'scheduled',
            'scheduled_at' => now()->subDay()->format('Y-m-d\TH:i'),
        ]))->assertSessionHasErrors('scheduled_at');

        $this->actingAs($user)->post(route('post.store'), $this->validData($category, [
            'status' => 'scheduled', // quên chọn giờ
        ]))->assertSessionHasErrors('scheduled_at');
    }

    public function test_author_can_update_own_post(): void
    {
        $post = Post::factory()->create(['title' => 'Tiêu đề cũ']);
        $category = Category::factory()->create();

        $this->actingAs($post->user)
            ->put(route('post.update', $post), $this->validData($category, ['title' => 'Tiêu đề mới']))
            ->assertRedirect(route('post.index'));

        $this->assertSame('Tiêu đề mới', $post->fresh()->title);
    }

    public function test_other_member_cannot_edit_or_update_post(): void
    {
        $post = Post::factory()->create(['title' => 'Tiêu đề cũ']);
        $other = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($other)->get(route('post.edit', $post))->assertForbidden();

        $this->actingAs($other)
            ->put(route('post.update', $post), $this->validData($category, ['title' => 'Bị sửa trộm']))
            ->assertForbidden();

        $this->assertSame('Tiêu đề cũ', $post->fresh()->title);
    }

    public function test_deleting_moves_post_to_trash_and_it_can_be_restored(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->user)->delete(route('post.destroy', $post))->assertRedirect(route('post.index'));
        $this->assertSoftDeleted($post);

        $this->actingAs($post->user)->patch(route('post.restore', $post))->assertRedirect(route('post.trash'));
        $this->assertNotSoftDeleted($post);
    }

    public function test_author_can_force_delete_from_trash(): void
    {
        $post = Post::factory()->create();
        $post->delete();

        $this->actingAs($post->user)->delete(route('post.force-delete', $post))->assertRedirect(route('post.trash'));

        $this->assertModelMissing($post);
    }

    public function test_other_member_cannot_delete_or_restore_post(): void
    {
        $post = Post::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($other)->delete(route('post.destroy', $post))->assertForbidden();
        $this->assertNotSoftDeleted($post);

        $post->delete();
        $this->actingAs($other)->patch(route('post.restore', $post))->assertForbidden();
        $this->actingAs($other)->delete(route('post.force-delete', $post))->assertForbidden();
        $this->assertSoftDeleted($post);
    }
}
