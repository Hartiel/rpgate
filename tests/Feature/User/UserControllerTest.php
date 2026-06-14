<?php

declare(strict_types=1);

use App\Enums\ThemeEnum;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('authenticated user can fetch their profile via api', function () {
    // Arrange: Prepare user data
    $user = User::factory()->create();

    // Act: Send GET request to the api/user endpoint
    $response = $this->actingAs($user)
        ->getJson('/api/user');

    // Assert: Verify successful get user data
    $response->assertStatus(200)
        ->assertJsonPath('data.email', $user->email);
});

test('updating user settings updates database and clears redis cache', function () {
    // Arrange: Prepare user settings data
    $user = User::factory()->create([
        'settings' => ['theme' => ThemeEnum::DARK->value, 'compact_mode' => false],
    ]);

    // Act: Send GET request to the api/user endpoint and expect Cache
    $this->actingAs($user)->getJson('/api/user');
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Envia payload from FormRequest/DTO to update theme
    $payload = [
        'theme' => ThemeEnum::LIGHT->value,
        'compact_mode' => true,
    ];

    $response = $this->putJson('/api/user/settings', $payload);

    // Assert: Verify successful get user data from cache
    $response->assertStatus(200)
        ->assertJsonPath('data.settings.theme', ThemeEnum::LIGHT->value);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'settings->theme' => ThemeEnum::LIGHT->value,
    ]);

    expect(Cache::has("user:{$user->id}"))->toBeFalse();
});
