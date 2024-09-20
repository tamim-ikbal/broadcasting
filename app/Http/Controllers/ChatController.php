<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\User;
use App\Services\InboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {

        $inboxes = InboxService::getInboxes(Auth::id());

        return view('chats.index', compact('inboxes'));
    }

    public function messages(Request $request, $inboxId)
    {
        $inboxes = InboxService::getInboxes(Auth::id());
        $chat    = Inbox::query()
                        ->where(function ($query) {
                            $query->whereHas('creator', function ($query) {
                                $query->where('users.id', '=', Auth::id());
                            })
                                  ->orWhereHas('inboxable', function ($query) {
                                      $query->where('inboxes.inboxable_id', '=', Auth::id())
                                            ->where('inboxes.inboxable_type', '=', User::class);
                                  });
                        })
                        ->findOrFail($inboxId);

        return view('chats.message', compact('inboxes', 'chat'));
    }

}
