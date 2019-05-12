<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testNew(): void
    {
        $user = User::new(
            $name = 'name',
            $email = 'email'
        );
    
        self::assertNotEmpty($user);
        
        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);
        self::assertNotEmpty($user->password);
    
        self::assertFalse($user->isAdmin());
        self::assertTrue($user->isUser());
    }
}
