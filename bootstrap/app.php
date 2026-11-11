<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Đặt tên ngắn "admin" để dùng trong routes
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Ghi log mọi lần bị từ chối truy cập (403). Không return gì để Laravel vẫn hiện trang lỗi như thường
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 403) {
                Log::channel('blog')->warning('Truy cập bị từ chối (403)', [
                    'user_id' => $request->user()?->id,
                    'url'     => $request->fullUrl(),
                    'ip'      => $request->ip(),
                ]);
            }
        });
    })->create();
