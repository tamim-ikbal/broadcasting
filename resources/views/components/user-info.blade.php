<div class="flex items-center gap-2 {{ $direction === 'vertical' ? ' flex-col mb-4' : '' }}">
    <div class="avatar">
        <img
            src="{{ $user->avatar_url ?? '' }}"
            class="w-[60px] h-[60px] rounded-[100px]">
    </div>
    <div class="{{ $direction === 'vertical' ? 'flex flex-col items-center' : '' }}">
        <h4 class="text-base font-semibold">
            {{ $user->name?? '' }}
        </h4>
        <div class="text-xs text-gray-300">
            <livewire:online-status
                :userId="$user->id"
                wire:key="os-{{$user->name ?? ''.$user->id}}"/>
        </div>
    </div>
</div>
