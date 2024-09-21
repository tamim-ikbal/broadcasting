<ul class="flex gap-4 flex-col lg:gap-2">
    @if(count($inboxes) > 0)
        @foreach($inboxes as $inbox)
            <li class="w-full">
                <x-link :href="route('chat.messages',$inbox->id)"
                        :active="request()->is('chats/'.$inbox->id)"
                        wire:navigate>
                    @if($inbox->creator->id === Auth::id())
                        <x-user-info :user="$inbox->inboxable"/>
                    @else
                        <x-user-info :user="$inbox->creator"/>
                    @endif
                </x-link>
            </li>
        @endforeach
    @endif
</ul>
