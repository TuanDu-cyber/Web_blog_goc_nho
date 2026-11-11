<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name'                  => 'Nguyễn Văn A',
            'email'                 => 'a@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'a@example.com']);
        Mail::assertSent(WelcomeMail::class);
    }

    public function test_register_fails_with_invalid_data(): void
    {
        $this->post('/register', [
            'name'                  => '',
            'email'                 => 'khong-phai-email',
            'password'              => '123',
            'password_confirmation' => 'khac',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_register_cannot_grant_admin_role(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name'                  => 'Kẻ gian',
            'email'                 => 'hacker@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
            'role'                  => 'admin', // cố tình gửi thêm trường role
        ]);

        $this->assertSame('member', User::where('email', 'hacker@example.com')->firstOrFail()->role);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['email' => 'b@example.com']); // mật khẩu mặc định của factory: "password"

        $this->post('/login', ['email' => 'b@example.com', 'password' => 'password'])
            ->assertRedirect(route('post.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'b@example.com']);

        $this->post('/login', ['email' => 'b@example.com', 'password' => 'sai-mat-khau'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
