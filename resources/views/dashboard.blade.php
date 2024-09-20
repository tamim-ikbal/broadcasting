<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <x-card>
                <livewire:friends.friends-list/>
            </x-card>
        </div>
    </div>
</x-app-layout>
