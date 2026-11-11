<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\SearchPost;
use App\Models\Category;
use App\Models\Tag;

class HomeController extends Controller
{

    public function index(SearchPost $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categorySlug = $request->query('category');
        $tagSlug = $request->query('tag');
        $isFiltering = $q !== '' || filled($categorySlug) || filled($tagSlug);

        $posts = Post::published()
            ->with(['category', 'user'])
            ->when($q !== '', function ($query) use ($q) {
                // escape % và _ để người dùng gõ ký tự này vẫn tìm đúng
                $like = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(function ($sub) use ($like) {
                    $sub->where('title', 'like', $like)
                        ->orWhere('content', 'like', $like);
                });
            })
            ->when($categorySlug, fn($query) => $query->whereHas('category', fn($c) => $c->where('slug', $categorySlug)))
            ->when($tagSlug, fn($query) => $query->whereHas('tags', fn($t) => $t->where('slug', $tagSlug)))
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc') // phá thế hòa: nhiều bài cùng giờ đăng không bị lặp/sót giữa các trang
            ->paginate(9)
            ->withQueryString();      // giữ q, category, tag khi bấm sang trang khác

        // Chỉ trang 1 khi không lọc mới có khối "Mới nhất" + "Nổi bật" (4 bài đầu)
        $showTop = ! $isFiltering && $posts->currentPage() === 1;
        $items = $posts->getCollection();
        $top = $showTop ? $items->take(4) : collect();
        $list = $showTop ? $items->slice(4) : $items;

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('home', [
            'posts'          => $posts,
            'hero'           => $top->first(),
            'featured'       => $top->slice(1, 3),
            'list'           => $list,
            'categories'     => $categories,
            'tags'           => $tags,
            'q'              => $q,
            'categorySlug'   => $categorySlug,
            'tagSlug'        => $tagSlug,
            'isFiltering'    => $isFiltering,
            'activeCategory' => $categories->firstWhere('slug', $categorySlug),
            'activeTag'      => $tags->firstWhere('slug', $tagSlug),
        ]);
    }

    public function show(Post $post)
    {
        $post->load(['user', 'category', 'tags']);
        abort_unless(
            $post->status === Post::STATUS_PUBLISHED
                || $post->user_id === auth()->id()
                || auth()->user()?->role === 'isAdmin',
            404
        );


        // Gán sẵn bài viết cho từng bình luận để CommentPolicy (@can('delete')) không phải truy vấn lại bài viết
        $comments = $post->comments()->with('user')->latest()->get()
            ->each(fn($comment) => $comment->setRelation('post', $post));

        $latest = Post::published()->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $related = Post::published()->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('article', compact('post', 'comments', 'latest', 'related'));
    }
}
