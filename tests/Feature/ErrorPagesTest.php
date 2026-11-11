<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_url_shows_custom_404_page(): void
    {
        $this->get('/khong-co-trang-nay')->assertNotFound()->assertSee('Không tìm thấy trang');
    }

    public function test_forbidden_request_shows_custom_403_page(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('admin.dashboard'))
            ->assertForbidden()
            ->assertSee('Bạn không có quyền truy cập');
    }
}
