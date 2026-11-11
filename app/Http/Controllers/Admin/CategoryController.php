<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('posts')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        Log::channel('blog')->info('Admin thêm danh mục', ['category_id' => $category->id, 'admin_id' => Auth::id()]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        Log::channel('blog')->info('Admin sửa danh mục', ['category_id' => $category->id, 'admin_id' => Auth::id()]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        // Không cho xóa danh mục còn bài viết (kể cả bài trong thùng rác) để bài không bị mồ côi
        if ($category->posts()->withTrashed()->exists()) {
            return back()->with('error', 'Danh mục đang có bài viết, không thể xóa.');
        }

        $category->delete();

        Log::channel('blog')->info('Admin xóa danh mục', ['category_id' => $category->id, 'admin_id' => Auth::id()]);

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục.');
    }
}
