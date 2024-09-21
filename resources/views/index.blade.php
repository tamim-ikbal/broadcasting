<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Friends Suggestion') }}
        </h2>
    </x-slot>
    <div class="py-5 lg:py-12">
        <div class="container">
            <x-card>
                <livewire:friend-suggestion/>
            </x-card>
        </div>
    </div>
</x-app-layout>
