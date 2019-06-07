<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    public function testLogin(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302)
            ->assertSee('login');
    }
}
