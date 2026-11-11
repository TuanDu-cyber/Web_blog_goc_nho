<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    // Mọi bài viết của toàn trang (trừ bài trong thùng rác của tác giả)
    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::with(['user', 'category'])
                ->withCount('comments')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }

    // Xóa bài vi phạm: xóa hẳn, tác giả không khôi phục được
    public function destroy(Post $post)
    {
        if ($post->image && str_starts_with($post->image, 'upload/')) {
            File::delete(public_path($post->image));
        }

        $post->forceDelete();

        Log::channel('blog')->warning('Admin xóa bài viết vi phạm', [
            'post_id'   => $post->id,
            'title'     => $post->title,
            'author_id' => $post->user_id,
            'admin_id'  => Auth::id(),
        ]);

        return back()->with('success', 'Đã xóa bài viết.');
    }
}
