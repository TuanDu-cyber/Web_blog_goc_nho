<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_admin_role_is_recognized_as_admin(): void
    {
        $user = new User();
        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->isAdmin());
    }

    public function test_member_role_is_not_admin(): void
    {
        $user = new User();
        $user->role = User::ROLE_MEMBER;

        $this->assertFalse($user->isAdmin());
    }
}
