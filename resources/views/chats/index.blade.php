@use('Illuminate\Support\Facades\Auth')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chats') }}
        </h2>
    </x-slot>
    <div class="py-5 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-6 lg:px-6 text-gray-900">
                    {{--Chats Wrapper--}}
                    <div class="flex gap-6">
                        {{--Sidebar--}}
                        <div class="w-full lg:w-[250px] h-auto lg:h-[calc(100vh_-_260px)] lg:overflow-y-auto lg:border-r-[1px] lg:border-gray-100 lg:shrink-0">
                            <livewire:chat.inbox-list/>
                        </div>
                        {{--Chatbox--}}
                        <div class="w-full bg-gray-50 hidden lg:block">
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

