<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OnlineStatusService
{
    const ONLINE_CACHE_KEY_PREFIX = 'online_status';

    public static function setOnlineStatus(): void
    {
        Cache::put(static::generateCacheKey(), true, 300); // 300 seconds/ 5 minutes
    }

    public static function isUserOnline($userId = null): bool
    {
        return Cache::has(static::generateCacheKey($userId));
    }

    public static function getOnlineStatus($userId = null): bool|null|string
    {
        return Cache::get(static::generateCacheKey($userId));
    }

    public static function generateCacheKey($userId = null): string
    {
        if ($userId == null) {
            $userId = Auth::id();
        }

        return self::ONLINE_CACHE_KEY_PREFIX.':'.$userId;
    }

}
