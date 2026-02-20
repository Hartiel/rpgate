<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'description', 'owner_id', 'is_active', 'is_private'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_private' => 'boolean',
    ];

    /**
     * Get the room's owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the room's sheets
     */
    public function sheets(): HasMany
    {
        return $this->hasMany(Sheet::class, 'room_id');
    }

    /**
     * Get the room's channels
     */
    public function channels(): MorphMany
    {
        return $this->morphMany(Channel::class, 'channelable')->orderBy('position');
    }
}
