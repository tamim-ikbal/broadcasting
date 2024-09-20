<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Events\MessageTyping;
use App\Models\Message;
use App\Services\FriendsService;
use App\Services\InboxService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SendMessage extends Component
{

    #[Validate('required')]
    #[Validate('string')]
    public string $message;

    public $inbox;

    public function render()
    {
        return view('livewire.chat.send-message');
    }

    public function startTyping()
    {
        broadcast(new MessageTyping($this->inbox, true))->toOthers();
    }

    public function endTyping()
    {
        broadcast(new MessageTyping($this->inbox, false))->toOthers();
    }

    public function save()
    {
        $this->validate();

        MessageService::maskAsRead($this->inbox->id);

        $message = Auth::user()->messages()->create([
            'message'  => $this->message,
            'inbox_id' => $this->inbox->id,
        ]);

        event(new MessageSent($message, $this->inbox));

        $this->reset('message');

        //
        MessageService::markAsUnread($this->inbox->id, $this->inbox->inboxable_id);

        InboxService::clearCache($this->inbox->creator_id);
        InboxService::clearCache($this->inbox->inboxable_id);
    }

}
