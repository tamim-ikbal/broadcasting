<?php

namespace App\Livewire\Chat;

use App\Services\InboxService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InboxList extends Component
{

    public function render()
    {
        $inboxes = InboxService::getInboxes(Auth::id());

        return view('livewire.chat.inbox-list', compact('inboxes'));
    }
}
