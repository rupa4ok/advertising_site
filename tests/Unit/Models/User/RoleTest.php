<?php

namespace Tests\Unit\Models\User;


use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    public function testChange(): void
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);
        self::assertFalse($user->isAdmin());
        
        $user->changeRole(User::ROLE_ADMIN);
        self::assertTrue($user->isAdmin());
    }
    
    public function testAlready(): void
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        $this->expectExceptionMessage('Role is already assigned');
    
        $user->changeRole(User::ROLE_ADMIN);
    }
}
