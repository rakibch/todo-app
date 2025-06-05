<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

beforeEach(function () {
    DB::table('users')->delete();
});

it('can register a user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated(); // 201
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('can login with correct credentials', function () {
    User::factory()->create([
        'email' => 'test123@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test123@example.com',
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'user',
        'token',
    ]);
});

it('can fetch authenticated user info', function () {
    Passport::actingAs(
        User::factory()->create(),
        ['*']
    );

    $response = $this->getJson('/api/user');

    $response->assertOk();
    $response->assertJsonStructure([
        'id',
        'name',
        'email',
    ]);
});

it('can logout successfully', function () {
    Passport::actingAs(
        User::factory()->create(),
        ['*']
    );

    $response = $this->postJson('/api/logout');

    $response->assertOk();
    $response->assertJson([
        'message' => 'Logged out successfully',
    ]);
});
