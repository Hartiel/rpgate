<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('new user can register by api', function () {
    // Arrange: Prepare valid registration data
    $payload = [
        'name' => 'User Test',
        'username' => 'usertest',
        'email' => 'usertest@rpgate.com',
        'password' => 'Password!123',
        'password_confirmation' => 'Password!123',
    ];

    // Act: Send POST request to the register endpoint
    $response = $this->postJson('/register', $payload);

    // Assert: Verify successful creation and database integrity
    $response->assertStatus(201);

    $this->assertDatabaseHas('users', [
        'email' => 'usertest@rpgate.com',
        'username' => 'usertest',
    ]);

    // Validate ULID, Discriminator and Hash
    $user = User::where('email', 'usertest@rpgate.com')->first();

    expect($user->id)->toBeString()->toHaveLength(26)
        ->and($user->discriminator)->toBeInt()->toBeBetween(1000, 9999)
        ->and(Hash::check('Password!123', $user->password))->toBeTrue();
});

test('Fail register if email in use', function () {
    // Arrange: Seed an existing user and prepare duplicate payload
    User::factory()->create([
        'email' => 'usertest@rpgate.com',
    ]);

    $payload = [
        'name' => 'User Test',
        'username' => 'usertest',
        'email' => 'usertest@rpgate.com',
        'password' => 'Password!123',
        'password_confirmation' => 'Password!123',
    ];

    // Act: Attempt to register with the duplicated email
    $response = $this->postJson('/register', $payload);

    // Assert: Expect a 422 validation error
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
