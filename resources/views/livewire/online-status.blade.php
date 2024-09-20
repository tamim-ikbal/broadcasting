@use('\App\Services\OnlineStatusService')
<span>
    @if(OnlineStatusService::isUserOnline($userId))
        <b class="text-green-500">Online</b>
    @else
        <b>Offline</b>
    @endif
</span>
