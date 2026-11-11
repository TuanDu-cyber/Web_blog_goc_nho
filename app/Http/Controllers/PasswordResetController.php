<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class PasswordResetController extends Controller
{
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(ForgotPasswordRequest $request)
    {
        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Gửi mail cấp lại mật khẩu thất bại', ['email' => $request->email, 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Không gửi được email lúc này, vui lòng thử lại sau.');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput()->with('error', 'Bạn thao tác quá nhanh, vui lòng đợi một lát rồi thử lại.');
        }

        if ($status === Password::RESET_LINK_SENT) {
            Log::channel('auth')->info('Yêu cầu cấp lại mật khẩu', [
                'email' => $request->email,
                'time'  => now()->toDateTimeString(),
                'ip'    => $request->ip(),
            ]);
        }

        // Luôn báo giống nhau để không lộ email nào đã đăng ký
        return back()->with('success', 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi liên kết cấp lại mật khẩu.');
    }

    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password'       => $password, // cast 'hashed' trong User tự mã hóa
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                Log::channel('auth')->info('Đổi mật khẩu', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'time'    => now()->toDateTimeString(),
                    'ip'      => $request->ip(),
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công, hãy đăng nhập lại.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Liên kết không hợp lệ hoặc đã hết hạn.']);
    }
}
