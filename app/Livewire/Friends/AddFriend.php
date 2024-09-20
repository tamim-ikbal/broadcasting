<?php

namespace App\Livewire\Friends;

use App\Services\FriendsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddFriend extends Component
{
    #[Validate('required')]
    public $id;
    public $isAdded = false;

    public function render()
    {
        return view('livewire.friends.add-friend');
    }

    public function save()
    {
        $this->validate();

        if (FriendsService::isFriendsWith(Auth::user(), $this->id)) {
            dd('Already added');
        }

        FriendsService::addFriend(Auth::user(), $this->id);

        $this->dispatch('friends.added', id: $this->id);
    }
}
