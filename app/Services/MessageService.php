<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MessageService
{
    public static $UNREAD_CACHE_KEY = 'message_unread_';

    public static function maskAsRead($inboxId)
    {
        if (Cache::has(self::generateUnreaCachedKey($inboxId))) {
            Message::query()->where('inbox_id', $inboxId)->update([
                'is_read' => true
            ]);

            Cache::forget(self::generateUnreaCachedKey($inboxId));
        }
    }

    public static function markAsUnread($inboxId, $userId = null)
    {
        Cache::put(self::generateUnreaCachedKey($inboxId, $userId), true);
    }

    public static function generateUnreaCachedKey($inboxId, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Auth::id();
        }

        return self::$UNREAD_CACHE_KEY.$inboxId.$userId;
    }

}
