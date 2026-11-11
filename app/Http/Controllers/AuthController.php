<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::channel('auth')->info('Đăng ký', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'time'    => now()->toDateTimeString(),
            'ip'      => $request->ip(),
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable $e) {
            Log::error('Gửi mail chào mừng thất bại', ['email' => $user->email, 'error' => $e->getMessage()]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Đăng ký thành công, chào mừng bạn!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credential = $request->validated();

        if (Auth::attempt($credential, $request->boolean('remember'))) {
            $request->session()->regenerate();

            Log::channel('auth')->info('Đăng nhập', [
                'user_id' => Auth::id(),
                'email'   => Auth::user()->email,
                'time'    => now()->toDateTimeString(),
                'ip'      => $request->ip(),
            ]);

            return redirect()->route('post.index');
        }

        Log::channel('auth')->warning('Đăng nhập thất bại', [
            'email' => $request->email,
            'time'  => now()->toDateTimeString(),
            'ip'    => $request->ip(),
        ]);

        return back()
            ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất.');
    }
}
