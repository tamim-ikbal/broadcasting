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
        return view('chats.index');
    }

    public function messages(Request $request, $inboxId)
    {
        $inbox = Inbox::query()
                      ->with(
                          ['creator' => fn($query) => $query->select('id', 'name', 'avatar')],
                          ['inboxable' => fn($query) => $query->select('id', 'name', 'avatar')]
                      )
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

        return view('chats.message', compact('inbox'));
    }

}
