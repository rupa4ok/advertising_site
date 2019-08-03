<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testForm(): void
    {
        $response = $this->get('/login');
        
        $response
            ->assertStatus(200)
            ->assertSee('Login');
    }
    
    public function testErrors(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);
        
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }
    
    public function testWait(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_WAIT]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        
        $response
            ->assertStatus(302)
            ->assertRedirect('/cabinet');
    }
    
    public function testActive(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        
        $response
            ->assertStatus(302)
            ->assertRedirect('/cabinet');
        
        $this->assertAuthenticated();
    }
}
