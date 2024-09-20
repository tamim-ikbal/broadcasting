@use('Illuminate\Support\Facades\Auth')
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
                            <ul class="flex gap-2 h-[620px] overflow-y-auto flex-col">
                                @if(count($inboxes) > 0)
                                    @foreach($inboxes as $inbox)
                                        <li class="w-full">
                                            <x-link :href="route('chat.messages',$inbox->id)"
                                                    :active="request()->is('chats/'.$inbox->id)"
                                                    wire:navigate>
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
                                <div class="flex justify-center min-h-[650px] items-center">
                                    <p class="text-base">Please select a friend to start chat:)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

