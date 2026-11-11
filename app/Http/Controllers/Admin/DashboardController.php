<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return view('admin.dashboard', [
            'memberCount'  => User::where('role', User::ROLE_MEMBER)->count(),
            'postCount'    => Post::published()->count(),
            'commentCount' => Comment::count(),
            'topPosts'     => Post::published()
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->orderByDesc('id')
                ->take(5)
                ->get(),
        ]);
    }
}
