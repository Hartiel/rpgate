<?php

use App\Models\User;

test('test scopeActive return only active users', function () {
    User::factory()->create(['is_active' => true]);
    User::factory()->create(['is_active' => false]);

    expect(User::active()->count())->toBe(1);
});

test('test scopeSearch filter username or email', function () {
    User::factory()->create(['username' => 'mestre_rpg', 'email' => 'mestre@rpg.com']);
    User::factory()->create(['username' => 'jogador_um', 'email' => 'player@rpg.com']);

    expect(User::search('mestre')->count())->toBe(1);
    expect(User::search('player')->count())->toBe(1);
});