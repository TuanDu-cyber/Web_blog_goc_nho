<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Giao diện phân trang riêng (mặc định của Laravel dùng Tailwind, project này không có)
        Paginator::defaultView('vendor.pagination.blog');

        View::composer('partials.header', function ($view) {
            $menus = [
                ['name' => 'Trang chủ', 'url' => route('home')],
            ];

            // Mỗi danh mục là một liên kết lọc: /?category=slug
            foreach (Category::orderBy('id')->get() as $category) {
                $menus[] = [
                    'name' => $category->name,
                    'url'  => route('home', ['category' => $category->slug]),
                ];
            }

            $view->with('menus', $menus);
        });
    }
}
