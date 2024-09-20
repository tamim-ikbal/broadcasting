<?php

namespace App\Livewire\Friends;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FriendsList extends Component
{
    public function render()
    {
        $friends = Auth::user()->friends()->paginate(20);

        return view('livewire.friends.friends-list', compact('friends'));
    }

    #[On('friends.removed')]
    public function listen()
    {
        $this->render();
    }
}
