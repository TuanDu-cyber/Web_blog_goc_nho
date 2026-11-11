<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\NewCommentMail;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function store(StoreComment $request, Post $post)
    {
        // Chỉ được bình luận dưới bài đã đăng (chặn ở server, không chỉ ẩn form)
        abort_unless($post->status === Post::STATUS_PUBLISHED, 404);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),   // lấy theo người đăng nhập, không lấy từ form
            'content' => $request->content,
        ]);

        Log::channel('blog')->info('Bình luận mới', [
            'comment_id' => $comment->id,
            'post_id'    => $post->id,
            'user_id'    => Auth::id(),
        ]);

        // Báo cho tác giả qua Queue; tác giả tự bình luận bài của mình thì không cần báo
        if ((int) $post->user_id !== (int) Auth::id()) {
            Mail::to($post->user)->queue(new NewCommentMail($comment));
        }

        return redirect(route('article.show', $post) . '#comments')
            ->with('success', 'Đã gửi bình luận.');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment); // người không có quyền -> 403

        $comment->delete();

        Log::channel('blog')->info('Xóa bình luận', [
            'comment_id' => $comment->id,
            'by_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
