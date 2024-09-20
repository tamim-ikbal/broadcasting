<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chats') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{--Chats Wrapper--}}
                    <div class="flex gap-6">
                        {{--Sidebar--}}
                        <div class="w-[250px] border-r-[1px] border-gray-100 shrink-0">
                            <ul class="flex gap-2 h-[600px] overflow-y-auto flex-col">
                                @if(count($inboxes) > 0)
                                    @foreach($inboxes as $inbox)
                                        <li class="w-full">
                                            <x-link :href="route('chat.messages',$inbox->id)"
                                                    :active="request()->is('chats/'.$inbox->id)">
                                                <div class="flex items-center gap-2">
                                                    <div class="avatar">
                                                        <img src="https://placehold.co/100x100"
                                                             class="w-[60px] h-[60px] rounded-[100px]">
                                                    </div>
                                                    <div>
                                                        <h4 class="text-base font-semibold">
                                                            @if($inbox->creator->id === Auth::id())
                                                                {{ $inbox->inboxable->name ?? '' }}
                                                            @else
                                                                {{ $inbox->creator->name?? '' }}
                                                            @endif
                                                        </h4>
                                                        <div class="text-xs text-gray-300">
                                                            @if($inbox->creator->id === Auth::id())
                                                                <livewire:online-status
                                                                    :userId="$inbox->inboxable->id"
                                                                    wire:key="os-{{$inbox->inboxable->id}}"/>
                                                            @else
                                                                <livewire:online-status
                                                                    :userId="$inbox->creator->id"
                                                                    wire:key="os-{{ $inbox->creator->id }}"/>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </x-link>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        {{--Chatbox--}}
                        <div class="w-full bg-gray-50">
                            <div class="flex flex-col justify-end">
                                {{--Chats--}}
                                <div class="h-[600px] p-3 overflow-y-auto" id="chat-box"
                                     x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); }}"
                                     x-intersect="scroll()">
                                    <livewire:chat.messages :inbox="$chat" wire:key="msi-{{$chat->id}}"/>
                                    <div
                                        class="max-w-[80%] md:max-w-[75%] lg:max-w-[70%] px-7 pt-0 pb-3 w-20 rounded-[10px] border-[1px] border-gray-200 self-start text-center flex items-center hidden"
                                        id="chat-typing">
                                        <p class="text-2xl animate-typing">.....</p>
                                    </div>
                                </div>
                                {{--Input--}}
                                <livewire:chat.send-message :inbox="$chat" wire:key="smi-{{$chat->id}}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Echo.private('messages.' + {{ auth()->id() }})
                    .listen('MessageSaved', (e) => {
                        // let h3 = document.createElement('h3');
                        // h3.textContent = e.message;
                        // h3.classList.add(e.className); // Add class to indicate sender
                        // document.getElementById('messages').appendChild(h3)
                        console.log(e)
                    });
            })
        </script>
    </x-slot>
</x-app-layout>

