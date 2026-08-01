<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasDiscriminator;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'discriminator', 'email', 'password', 'settings'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasDiscriminator, HasFactory, HasUlids, Notifiable;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'discriminator' => 'integer',
            'settings' => 'array',
        ];
    }

    /**
     * 🤝 Relationship 1: Friendships where my ULID is the SMALLER (user_id)
     */
    public function friendshipsAsUser(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->using(Friendship::class)
            ->withPivot('id', 'action_user_id', 'status')
            ->withTimestamps();
    }

    /**
     * Relationship 2: Friendships where my ULID is the LARGER (friend_id)
     */
    public function friendshipsAsFriend(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->using(Friendship::class)
            ->withPivot('id', 'action_user_id', 'status')
            ->withTimestamps();
    }

    /**
     * Unified friend list (Only accepted friendships)
     * Returns a clean collection of 'User' objects that are friends with this user.
     */
    public function getFriendsAttribute(): Collection
    {
        // Search friends on left (where I am user_id)
        $leftFriends = $this->friendshipsAsUser()
            ->wherePivot('status', 'accepted')
            ->get();

        // Search friends from right (where I am friend_id)
        $rightFriends = $this->friendshipsAsFriend()
            ->wherePivot('status', 'accepted')
            ->get();

        // Merge the two collections
        return $leftFriends->merge($rightFriends);
    }

    /**
     * Received invitation (Waiting for my approval)
     */
    public function getPendingRequestsAttribute(): Collection
    {
        // If status is pending and action_user_id not me, then I received invitation
        return $this->friendshipsAsUser()->wherePivot('status', 'pending')->wherePivot('action_user_id', '!=', $this->id)->get()
            ->merge(
                $this->friendshipsAsFriend()->wherePivot('status', 'pending')->wherePivot('action_user_id', '!=', $this->id)->get()
            );
    }

    /**
     * Sent requests (Waiting for the other to accept)
     */
    public function getSentRequestsAttribute(): Collection
    {
        // If status is pending and action_user_id is me, then I sent the request
        return $this->friendshipsAsUser()->wherePivot('status', 'pending')->wherePivot('action_user_id', $this->id)->get()
            ->merge(
                $this->friendshipsAsFriend()->wherePivot('status', 'pending')->wherePivot('action_user_id', $this->id)->get()
            );
    }
}
