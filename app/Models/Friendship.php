<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table('friendships')]
#[Fillable(['user_id', 'friend_id', 'action_user_id', 'status'])]
class Friendship extends Pivot
{
    use HasUlids;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    public function actionUser()
    {
        return $this->belongsTo(User::class, 'action_user_id');
    }
}
