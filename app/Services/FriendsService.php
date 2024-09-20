<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class FriendsService
{
    public static function addFriend(User|Authenticatable $user, $friends): array
    {
        if ( ! is_array($friends)) {
            $friends = [$friends];
        }

        return $user->friends()->syncWithoutDetaching($friends);
    }

    public static function isFriendsWith(User|Authenticatable $user, array|int $friends): bool
    {
        if ( ! is_array($friends)) {
            $friends = [$friends];
        }

        return $user->friends()->where(function (Builder $builder) use ($friends) {
                $builder->whereIntegerInRaw('friends.user_id', $friends)->orWhereIntegerInRaw('friends.friend_id',
                    $friends);
            })->count() > 0;
    }

    public static function unfriend(User|Authenticatable $user, $ids): int|false
    {
        if ( ! is_array($ids)) {
            $ids = [$ids];
        }

        if ( ! static::isFriendsWith($user, $ids)) {
            return false;
        }

        return $user->friends()->detach($ids);
    }

}
