<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $user,
        public string $direction = 'horizontal',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-info');
    }
}
