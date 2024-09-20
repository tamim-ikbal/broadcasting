<x-button variant="primary" wire:click="save" wire:loading.attr="disabled">
    {{ !$isAdded ? __('Add Friend') : __('Friend Added') }}
</x-button>

