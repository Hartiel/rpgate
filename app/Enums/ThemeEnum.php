<?php

namespace App\Enums;

enum ThemeEnum: string
{
    case LIGHT = 'light';
    case DARK = 'dark';
    case SYSTEM = 'system';

    /**
     * Retorna os valores em formato de array para validação.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}