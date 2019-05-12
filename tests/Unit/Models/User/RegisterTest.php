<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testNew(): void
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );
    
        self::assertNotEmpty($user);
        
        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);
        self::assertNotEmpty($user->password);
        self::assertNotEquals($password, $user->password);
    
        self::assertFalse($user->isAdmin());
        self::assertFalse($user->isWait());
        self::assertFalse($user->isActive());
        self::assertTrue($user->isUser());
    }
    
    public function testVerify(): void
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );
        
        $user->verify();

        self::assertFalse($user->isWait());
        self::assertFalse($user->isActive());
    }
}
