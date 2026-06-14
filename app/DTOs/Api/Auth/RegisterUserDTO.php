<?php

declare(strict_types=1);

namespace App\DTOs\Api\Auth;

/**
 * Data Transfer Object for new users register.
 */
readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $username,
        public string $email,
        public string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            username: $data['username'],
            email: $data['email'],
            password: $data['password'],
        );
    }
}
