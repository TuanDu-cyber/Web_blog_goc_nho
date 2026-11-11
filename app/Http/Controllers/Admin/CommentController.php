<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index()
    {
        return view('admin.comments.index', [
            'comments' => Comment::with(['user', 'post'])->latest()->paginate(15),
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        Log::channel('blog')->warning('Admin xóa bình luận', [
            'comment_id' => $comment->id,
            'admin_id'   => Auth::id(),
        ]);

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
