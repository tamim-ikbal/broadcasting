<?php

namespace App\Livewire\Friends;

use App\Services\FriendsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Unfriend extends Component
{
    public $friend;

    public function render()
    {
        return view('livewire.friends.unfriend');
    }

    public function save()
    {
        FriendsService::unfriend(Auth::user(), $this->friend->id);
        $this->dispatch('friends.removed', id: $this->friend->id);
    }
}
