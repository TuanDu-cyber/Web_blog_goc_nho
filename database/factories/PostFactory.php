<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'title'        => fake()->sentence(6),
            // không điền slug: Post::booted() tự tạo từ tiêu đề
            'content'      => implode("\n\n", fake()->paragraphs(3)),
            'image'        => fake()->randomElement([
                'images/world5.jpg',
                'images/world2.jpg',
                'images/sport2.jpg',
                'images/business4.jpg',
                'images/entertainment1.jpg',
            ]),
            'status'       => Post::STATUS_PUBLISHED,
            'published_at' => now(),
            'scheduled_at' => null,
        ];
    }

    // Bài nháp: Post::factory()->draft()->create()
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Post::STATUS_DRAFT,
        ]);
    }

    // Bài hẹn giờ: Post::factory()->scheduled(now()->addDay())->create()
    public function scheduled(?\DateTimeInterface $at = null): static
    {
        $at ??= now()->addDay();

        return $this->state(fn(array $attributes) => [
            'status'       => Post::STATUS_SCHEDULED,
            'scheduled_at' => $at,
            'published_at' => $at, // cột published_at đang NOT NULL nên gán bằng giờ hẹn
        ]);
    }
}
