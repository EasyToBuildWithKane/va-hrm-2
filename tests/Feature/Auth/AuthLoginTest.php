<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token', 'user' => ['ulid', 'name', 'email']]]);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
            'status' => 'active',
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong',
        ])->assertStatus(422);
    }
}
