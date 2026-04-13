<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test student login page is accessible.
     */
    public function test_siswa_login_page_is_accessible(): void
    {
        $response = $this->get('/login/siswa');

        $response->assertStatus(200);
        $response->assertSee('Login Siswa');
    }

    /**
     * Test student login with valid credentials.
     */
    public function test_siswa_login_with_valid_credentials(): void
    {
        // NIS from database: 1.24.20513
        // Password for student accounts is usually set to 'password' or similar in test environments, 
        // but here we know the NIS. Let's assume '123456' based on AdminController create logic (size 6).
        // Actually, I'll create a dummy user to be sure.
        
        $user = User::create([
            'name' => 'Test Siswa',
            'nis' => '123456',
            'role' => 'siswa',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        ]);

        $response = $this->post('/login', [
            'nis' => '123456',
            'password' => '123456',
        ]);

        $response->assertRedirect('/siswa/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test student login with invalid credentials.
     */
    public function test_siswa_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'nis' => 'wrong_nis',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('login_failed');
        $this->assertGuest();
    }
}
