<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user can authenticate using valid credentials', function () {
    // Arrange: Create a valid user in the database
    $user = User::factory()->create([
        'email' => 'usertest@rpgate.com',
        'password' => Hash::make('Password!123'),
    ]);

    $payload = [
        'email' => 'usertest@rpgate.com',
        'password' => 'Password!123',
    ];

    // Act: Send POST request to Fortify's native login route
    $response = $this->postJson('/login', $payload);

    // Assert: Check for successful response and authentication state
    $response->assertSuccessful();
    $this->assertAuthenticatedAs($user);
});

test('user cannot authenticate with invalid password', function () {
    // Arrange: Create a user
    User::factory()->create([
        'email' => 'usertest@rpgate.com',
        'password' => Hash::make('Password!123'),
    ]);

    $payload = [
        'email' => 'usertest@rpgate.com',
        'password' => 'WrongPassword!999',
    ];

    // Act: Attempt login with wrong password
    $response = $this->postJson('/login', $payload);

    // Assert: Expect a 422 Unprocessable Entity and no active session
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    $this->assertGuest();
});

test('authenticated user can log out', function () {
    // Arrange: Create and authenticate a user
    $user = User::factory()->create();
    $this->actingAs($user);

    // Verify the user is actually authenticated before testing logout
    $this->assertAuthenticatedAs($user);

    // Act: Send POST request to the logout route
    $response = $this->postJson('/logout');

    // Assert: Verify the session is destroyed
    $response->assertSuccessful();
    $this->assertGuest();
});
