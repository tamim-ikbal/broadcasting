<?php

namespace App\Livewire\Friends;

use App\Models\User;
use App\Services\FriendsService;
use App\Services\InboxService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendMessage extends Component
{
    public $friend;
    public $btnVariant = 'primary';

    public function render()
    {
        return view('livewire.friends.send-message');
    }

    public function save()
    {

//        if ( ! FriendsService::isFriendsWith(Auth::user(), $this->friend->id)) {
//            session()->flash('error', 'You cannot send a message to this user.');
//
//            return;
//        }

        $inbox = InboxService::createInbox(Auth::user(), $this->friend->id, User::class);
        if ( ! $inbox) {
            session()->flash('error', 'Failed to create inbox.');

            return;
        }

        $this->redirectRoute('chat.messages', $inbox->id);
    }
}
