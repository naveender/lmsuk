<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('check username endpoint returns true when available', function () {
    $response = $this->postJson(route('register.check-username'), [
        'username' => 'new_user_123',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'available' => true,
        'message' => 'Username is available!',
    ]);
});

test('check username endpoint returns false and suggestions when taken', function () {
    // Create an existing user
    User::factory()->create([
        'username' => 'takenuser'
    ]);

    $response = $this->postJson(route('register.check-username'), [
        'username' => 'takenuser',
        'name' => 'Taken User',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'available' => false,
        'message' => 'Username is already taken.',
    ]);
    
    $data = $response->json();
    expect($data)->toHaveKey('suggestions');
    expect($data['suggestions'])->toHaveCount(3);
    // Ensure all suggestions are unique and don't match 'takenuser'
    foreach ($data['suggestions'] as $suggestion) {
        expect($suggestion)->not->toEqual('takenuser');
        expect(User::where('username', $suggestion)->exists())->toBeFalse();
    }
});

test('check username endpoint fails on invalid characters', function () {
    $response = $this->postJson(route('register.check-username'), [
        'username' => 'user name!', // Space and exclamation mark are invalid
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'available' => false,
    ]);
    
    $data = $response->json();
    expect($data)->toHaveKey('suggestions');
});

test('user registration fails if username is missing or invalid', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'student',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors(['username']);

    $response2 = $this->post('/register', [
        'name' => 'John Doe',
        'username' => 'jo', // too short
        'email' => 'john@example.com',
        'role' => 'student',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response2->assertSessionHasErrors(['username']);
});

test('user registration succeeds with a unique username', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'username' => 'johndoe_unique',
        'email' => 'john@example.com',
        'role' => 'student',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'username' => 'johndoe_unique',
        'email' => 'john@example.com',
        'role' => 'student',
    ]);
});

test('ajax forgot password request succeeds for existing email', function () {
    $user = User::factory()->create([
        'email' => 'registered@example.com'
    ]);

    $response = $this->postJson('/forgot-password', [
        'email' => 'registered@example.com'
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['message']);
});

test('ajax forgot password request fails with 422 for unregistered email', function () {
    $response = $this->postJson('/forgot-password', [
        'email' => 'notfound@example.com'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
