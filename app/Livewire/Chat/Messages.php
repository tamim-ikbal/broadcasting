<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class Messages extends Component
{
    public $inbox;
    public $messages = [];

    public function mount()
    {
        $chats          = Message::query()->whereBelongsTo($this->inbox)->get();
        $this->messages = $chats;
    }

    public function render()
    {
        return view('livewire.chat.messages');
    }

    #[On('messages.saved')]
    public function listenToNewMessage($inbox)
    {
        //dd($inbox);
    }

    public function getListeners()
    {
        return [
            "echo:messages.{$this->inbox->id},MessageSent" => 'messageUpdated',
        ];
    }

    public function messageUpdated($inbox)
    {
        dd($inbox);
    }
}
