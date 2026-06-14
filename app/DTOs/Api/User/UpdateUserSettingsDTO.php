<?php

namespace App\DTOs\Api\User;

use App\Http\Requests\Api\User\UpdateUserSettingsRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ThemeEnum;

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