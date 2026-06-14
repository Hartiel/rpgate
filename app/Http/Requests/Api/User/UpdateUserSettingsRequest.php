<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ThemeEnum;
use Illuminate\Validation\Rules\Enum;

class UpdateUserSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // O middleware do Sanctum já garante que está autenticado
    }

    public function rules(): array
    {
        return [
            'theme' => ['required', new Enum(ThemeEnum::class)],
            'compact_mode' => ['nullable', 'boolean'],
        ];
    }
}