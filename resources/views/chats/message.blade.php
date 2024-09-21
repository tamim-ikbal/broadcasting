<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('chat.index') }}" wire:navigate class="inline-flex gap-2 items-center bg-gray-100 px-4 py-2 rounded lg:hidden hover:bg-red-500 hover:text-white hover:fill-white transition duration-300">
            <svg id="fi_3114883" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"
                 data-name="Layer 2">
                <path
                    d="m22 11h-17.586l5.293-5.293a1 1 0 1 0 -1.414-1.414l-7 7a1 1 0 0 0 0 1.414l7 7a1 1 0 0 0 1.414-1.414l-5.293-5.293h17.586a1 1 0 0 0 0-2z"></path>
            </svg>
            Go Back
        </a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight hidden lg:block">
            {{ __('Chats') }}
        </h2>
    </x-slot>
    <div class="py-5 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{--Chats Wrapper--}}
                    <div class="flex gap-6">
                        {{--Sidebar--}}
                        <div
                            class="hidden lg:block lg:px-0 lg:w-[250px] lg:border-r-[1px] lg:border-gray-100 lg:shrink-0 lg:h-[calc(100vh_-_280px)] overflow-y-auto transition">
                            <livewire:chat.inbox-list/>
                        </div>
                        {{--Chatbox--}}
                        <div class="w-full bg-gray-50">
                            <div class="flex flex-col justify-end">
                                {{--Chats--}}
                                <div class="h-[calc(100vh_-_250px)] lg:h-[calc(100vh_-_320px)] p-3 overflow-y-auto" id="chat-box"
                                     x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); }}"
                                     x-intersect="scroll()">
                                    @if($inbox->creator->id === Auth::id())
                                        <x-user-info :user="$inbox->inboxable" direction="vertical"/>
                                    @else
                                        <x-user-info :user="$inbox->creator" direction="vertical"/>
                                    @endif
                                    <livewire:chat.messages :inbox="$inbox" wire:key="msi-{{$inbox->id}}"/>
                                    <div
                                        class="max-w-[80%] md:max-w-[75%] lg:max-w-[70%] px-7 pt-0 pb-3 w-20 rounded-[10px] border-[1px] border-gray-200 self-start text-center flex items-center hidden"
                                        id="chat-typing">
                                        <p class="text-2xl animate-typing">.....</p>
                                    </div>
                                </div>
                                {{--Input--}}
                                <livewire:chat.send-message :inbox="$inbox" wire:key="smi-{{$inbox->id}}"/>
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

