<?php

use App\Models\User;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;


// Helper to authenticate a user and get the auth headers
function authHeaders(User $user): array {
    $token = $user->createToken('TestToken')->accessToken;
    return ['Authorization' => 'Bearer ' . $token];
}

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('lists all todos for the authenticated user', function () {
    Todo::factory()->count(3)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/todos', authHeaders($this->user));

    $response->assertOk()
             ->assertJsonCount(3);
});

it('stores a new todo for the authenticated user', function () {
    $data = [
        'title' => 'Test Todo',
        'description' => 'This is a test todo',
        'due_date' => '2025-12-31',
    ];

    $response = $this->postJson('/api/todos', $data, authHeaders($this->user));

    $response->assertCreated()
             ->assertJsonFragment(['title' => 'Test Todo']);

    expect($this->user->todos()->count())->toBe(1);
});

it('shows a specific todo for the authenticated user', function () {
    $todo = Todo::factory()->create(['user_id' => $this->user->id]);

    $response = getJson("/api/todos/{$todo->id}", authHeaders($this->user));

    $response->assertOk()
             ->assertJsonFragment(['id' => $todo->id]);
});

it('updates a todo for the authenticated user', function () {
    $todo = Todo::factory()->create(['user_id' => $this->user->id]);

    $updateData = ['title' => 'Updated Title'];

    $response = putJson("/api/todos/{$todo->id}", $updateData, authHeaders($this->user));

    $response->assertOk()
             ->assertJsonFragment(['title' => 'Updated Title']);

    $this->assertDatabaseHas('todos', ['id' => $todo->id, 'title' => 'Updated Title']);
});

it('deletes a todo for the authenticated user', function () {
    $todo = Todo::factory()->create(['user_id' => $this->user->id]);

    $response = deleteJson("/api/todos/{$todo->id}", [], authHeaders($this->user));

    $response->assertOk()
             ->assertJson(['message' => 'Todo deleted successfully']);

    $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
});
