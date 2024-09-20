<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FriendSuggestion extends Component
{
    use WithPagination;

    public $alert;

    public function render()
    {
        $friends = User::query()
                       ->whereDoesntHave('friends', function (Builder $builder) {
                           $builder->where('friends.user_id', '=', Auth::id())
                                   ->orWhere('friends.friend_id', '=', Auth::id());
                       })
                       ->where('id', '!=', Auth::id())
                       ->orderBy('id', 'ASC')
                       ->paginate(20);

        return view('livewire.friend-suggestion', compact('friends'));
    }

    public function listen()
    {
        $this->render();
    }
}
