<div>
    @if(session()->has('error'))
        <div>{{ session('error') }}</div>
    @endif
    <div class="flex flex-col gap-4">
        @foreach($friends as $friend)
            <div class="flex items-center justify-between gap-6 border-b-[1px] border-gray-200 last:border-b-[0px] pb-2"
                 wire:key="friend-{{ $friend->id }}" id="friend-{{ $friend->id }}">
                <div class="flex gap-4 items-center">
                    <img src="{{ $friend->avatar_url }}"
                         class="w-[70px] h-[70px] rounded-[100px] object-cover object-top">
                    <div>
                        <h3 class="text-xl font-[700]">
                            {{ $friend->name ?? '' }}
                        </h3>
                        <p class="text-xs text-gray-300">
                            <livewire:online-status
                                :userId="$friend->id"
                                wire:key="os-{{$friend->id}}"/>
                        </p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <livewire:friends.unfriend :friend="$friend" wire:key="ufbtn-{{ $friend->id }}"/>
                    <livewire:friends.send-message :friend="$friend" wire:key="smbtn-{{ $friend->id }}"/>
                </div>
            </div>
        @endforeach
        @if($friends->hasPages())
            {{ $friends->links() }}
        @endif
    </div>
</div>

