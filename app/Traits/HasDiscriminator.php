<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Trait for manage discriminator creation (#0000).
 *
 * @mixin Model
 */
trait HasDiscriminator
{
    public static function bootHasDiscriminator(): void
    {
        static::creating(function (Model $model) {
            // Generate if not passed manually
            if (empty($model->discriminator) && !empty($model->username)) {
                $model->discriminator = static::generateUniqueDiscriminator($model->username, $model->getTable());
            }
        });
    }

    /**
     * Generate number between 1000 and 9999 that's unique with username.
     */
    protected static function generateUniqueDiscriminator(string $username, string $table): int
    {
        do {
            $discriminator = mt_rand(1000, 9999);

            $exists = DB::table($table)
                ->where('username', $username)
                ->where('discriminator', $discriminator)
                ->exists();

        } while ($exists);

        return $discriminator;
    }
}
