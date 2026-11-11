<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    //Danh sách bài viết của t ko gồm bài trong thùng rác
    public function index()
    {
        $posts = Post::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('published_at', 'desc')
            ->get();

        $trashedCount = Post::onlyTrashed()->where('user_id', Auth::id())->count();

        return view('posts.index', [
            'posts'        => $posts,
            'trashedCount' => $trashedCount,
        ]);
    }

    public function create()
    {
        return view('posts.create', [
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(StorePost $request)
    {
        $isScheduled = $request->status === Post::STATUS_SCHEDULED;

        // user_id tự gắn theo người đăng nhập, không lấy từ form
        $post = $request->user()->posts()->create([
            'title'        => $request->title,
            'category_id'  => $request->category_id,
            'content'      => $request->content,
            'image'        => $this->uploadImage($request->file('image_file')),
            'status'       => $request->status,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
            // bài hẹn giờ lấy luôn giờ hẹn làm ngày đăng (cột published_at không cho null)
            'published_at' => $isScheduled ? $request->scheduled_at : now(),
        ]);

        $post->tags()->sync($request->input('tags', []));
        Log::channel('blog')->info('Tạo bài viết', ['post_id' => $post->id, 'user_id' => $post->user_id, 'status' => $post->status]);

        return redirect()->route('post.index')->with('success', 'Đã lưu bài viết mới.');
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post); // không phải chủ bài -> 403

        return view('posts.edit', [
            'post'       => $post->load('tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(UpdatePost $request, Post $post)
    {
        //Quyền sở hữu đã được kiểm tra trong UpdatePost::authorize()
        $isScheduled = $request->status === Post::STATUS_SCHEDULED;

        $data = [
            'title'        => $request->title,
            'category_id'  => $request->category_id,
            'content'      => $request->content,
            'status'       => $request->status,
            'scheduled_at' => $isScheduled ? $request->scheduled_at : null,
        ];

        if ($isScheduled) {
            $data['published_at'] = $request->scheduled_at;
        } elseif ($request->status === Post::STATUS_PUBLISHED && $post->status !== Post::STATUS_PUBLISHED) {
            // vừa chuyển từ nháp/hẹn giờ sang đăng ngay -> ngày đăng là bây giờ
            $data['published_at'] = now();
        }

        // Chỉ khi chọn ảnh mới thì mới thay ảnh cũ
        if ($request->hasFile('image_file')) {
            $this->deleteImage($post->image);
            $data['image'] = $this->uploadImage($request->file('image_file'));
        }

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));
        Log::channel('blog')->info('Sửa bài viết', ['post_id' => $post->id, 'user_id' => Auth::id()]);


        return redirect()->route('post.index')->with('success', 'Đã cập nhật bài viết.');
    }

    // XÓA MỀM: chỉ đánh dấu deleted_at, giữ nguyên ảnh để còn khôi phục
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();
        Log::channel('blog')->info('Chuyển bài viết vào thùng rác', ['post_id' => $post->id, 'user_id' => Auth::id()]);

        return redirect()->route('post.index')->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    // Thùng rác của tôi
    public function trash()
    {
        $posts = Post::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('posts.trash', ['posts' => $posts]);
    }

    // Khôi phục bài viết từ thùng rác
    public function restore(Post $post)
    {
        Gate::authorize('restore', $post);

        $post->restore();
        Log::channel('blog')->info('Khôi phục bài viết', ['post_id' => $post->id, 'user_id' => Auth::id()]);

        return redirect()->route('post.trash')->with('success', 'Đã khôi phục bài viết.');
    }

    // Xóa vĩnh viễn: lúc này mới xóa dòng trong DB và file ảnh
    public function forceDelete(Post $post)
    {
        Gate::authorize('forceDelete', $post);

        $this->deleteImage($post->image);
        $post->forceDelete();
        Log::channel('blog')->warning('Xóa vĩnh viễn bài viết', ['post_id' => $post->id, 'user_id' => Auth::id()]);

        return redirect()->route('post.trash')->with('success', 'Đã xóa vĩnh viễn bài viết.');
    }

    // Lưu ảnh vào public/upload, trả về đường dẫn để lưu DB
    private function uploadImage(UploadedFile $file): string
    {
        // tên ngẫu nhiên + đuôi lấy theo nội dung file thật
        $fileName = $file->hashName();
        $file->move(public_path('upload'), $fileName);

        return 'upload/' . $fileName;
    }

    // Chỉ xóa ảnh do người dùng upload, không đụng ảnh mẫu trong images/
    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'upload/')) {
            File::delete(public_path($path));
        }
    }
}
