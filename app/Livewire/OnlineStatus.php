<?php

namespace App\Livewire;

use Livewire\Component;

class OnlineStatus extends Component
{
    public $userId;

    public function render()
    {
        return view('livewire.online-status');
    }
}
