<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    use HasUuids;

    protected $fillable = [
        'channelable_id',
        'channelable_type',
        'name',
        'type',
        'description',
        'position',
        'is_active',
        'is_private',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_private' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Get channel's messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get channel's parent model
     */
    public function channelable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get channel's users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
