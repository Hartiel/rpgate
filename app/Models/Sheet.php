<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sheet extends Model
{
    use HasUuids;

    protected $fillable = [
        'owner_id',
        'room_id',
        'name',
        'system',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get sheet's owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get sheet's rooms
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
