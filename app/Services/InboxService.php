<?php

namespace App\Services;

use App\Models\Inbox;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InboxService
{
    const INBOX_CACHE_KEY_PREFIX = 'inbox-users-';

    public static function createInbox(Authenticatable $user, int $inboxableId, string $inboxableType): Inbox|null
    {
        $inbox = Inbox::create([
            'creator_id'     => $user->id,
            'inboxable_id'   => $inboxableId,
            'inboxable_type' => $inboxableType
        ]);

        //Clear Cache
        self::clearCache($user->id);
        self::clearCache($inboxableId);

        return $inbox;
    }

    public static function getInboxes($userId)
    {
        return Cache::rememberForever(self::INBOX_CACHE_KEY_PREFIX.$userId, function () use ($userId) {
            return Inbox::query()
                        ->select('inboxes.*', DB::raw('MAX(messages.created_at) as last_message'))
                        ->leftJoin('messages', 'inboxes.id', '=', 'messages.inbox_id')
                        ->with(
                            ['creator' => fn($query) => $query->select('id', 'name')],
                            ['inboxable' => fn($query) => $query->select('id', 'name')],
                            ['latestMessage']
                        )
                        ->whereHas('creator', function ($query) use ($userId) {
                            $query->where('users.id', '=', $userId);
                        })
                        ->orWhereHas('inboxable', function ($query) use ($userId) {
                            $query->where('inboxes.inboxable_id', '=', $userId);
                        })
                        ->groupBy('inboxes.id')
                        ->orderBy('last_message', 'DESC')
                        ->orderBy('inboxes.id', 'DESC')
                        ->get();
        });
    }

    public static function clearCache($userId): void
    {
        Cache::forget(self::INBOX_CACHE_KEY_PREFIX.$userId);
    }
}
