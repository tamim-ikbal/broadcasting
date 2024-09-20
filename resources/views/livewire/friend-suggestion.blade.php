<div>

    <div class="flex flex-col gap-4" x-data="{isAdding:false}">
        @foreach($friends as $friend)
            <div class="flex items-center justify-between gap-6 border-b-[1px] border-gray-200 pb-2"
                 wire:key="{{ $friend->id }}" id="suggested-friend-{{ $friend->id }}">
                <div class="flex gap-4 items-center">
                    <img src="https://placehold.co/100x100" class="w-[70px] h-[70px] rounded-[100px]">
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
                <div>
                    <livewire:friends.add-friend :id="$friend->id" wire:key="button-{{ $friend->id }}"/>
                </div>
            </div>
        @endforeach
        {{ $friends->links() }}
    </div>
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
