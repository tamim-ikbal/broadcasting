<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-6 md:gap-4" x-data="{isAdding:false}">
    @foreach($friends as $friend)
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 border-b-[1px] border-gray-200 md:last:border-b-[0px] last:border-b-[0px] sm:last:border-b-[1px] pb-3 md:pb-2"
            wire:key="{{ $friend->id }}" id="suggested-friend-{{ $friend->id }}">
            <div class="flex gap-3 items-center">
                <img src="{{ $friend->avatar_url }}"
                     class="w-[50px] h-[50px] md:w-[70px] md:h-[70px] rounded-[100px] object-cover object-top">
                <div>
                    <h3 class="text-lg md:text-xl font-[700]">
                        {{ $friend->name ?? '' }}
                    </h3>
                    <p class="text-xs text-gray-300">
                        <livewire:online-status
                            :userId="$friend->id"
                            wire:key="os-{{$friend->id}}"/>
                    </p>
                </div>
            </div>
            <div>
                <livewire:friends.add-friend :id="$friend->id" wire:key="button-{{ $friend->id }}"/>
            </div>
        </div>
    @endforeach
    @if($friends->hasPages())
        {{ $friends->links() }}
    @endif
</div>
@script
<script>
    $wire.on('friends.added', ({id}) => {
        document.getElementById('suggested-friend-' + id).style.display = 'none';
        let refreshTime = setTimeout(function () {
            $wire.listen();
            console.log('Loaded...')
            clearTimeout(refreshTime);
        }, 5000)
    });
</script>
@endscript
