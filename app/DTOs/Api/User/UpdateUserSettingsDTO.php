<?php

namespace App\DTOs\Api\User;

use App\Enums\ThemeEnum;
use App\Http\Requests\Api\User\UpdateUserSettingsRequest;

class UpdateUserSettingsDTO
{
    public function __construct(
        public readonly ThemeEnum $theme,
        public readonly bool $compactMode,
    ) {}

    public static function fromRequest(UpdateUserSettingsRequest $request): self
    {
        return new self(
            theme: ThemeEnum::from($request->validated('theme')),
            compactMode: (bool) $request->validated('compact_mode', false),
        );
    }
}
