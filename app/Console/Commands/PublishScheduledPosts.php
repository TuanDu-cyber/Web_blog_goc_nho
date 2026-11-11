<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động đăng các bài viết hẹn giờ đã đến hạn';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Bài đang ở trạng thái hẹn giờ và đã đến (hoặc quá) giờ hẹn
        $posts = Post::where('status', Post::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($posts as $post) {
            $post->update([
                'status'       => Post::STATUS_PUBLISHED,
                'published_at' => now(),
            ]);

            Log::channel('blog')->info('Tự động đăng bài hẹn giờ', [
                'post_id' => $post->id,
                'title'   => $post->title,
            ]);
        }

        $this->info("Đã đăng {$posts->count()} bài viết hẹn giờ.");

        return self::SUCCESS;
    }
}
