<?php


namespace Tests\Unit\Models\User;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRequest()
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
    
        self::assertTrue($user->isWait);
        self::assertFalse($user->isActive);
    }
    
//    public function testVerify()
//    {
//        $user = User::register('name', 'email', 'password');
//
//    }
}