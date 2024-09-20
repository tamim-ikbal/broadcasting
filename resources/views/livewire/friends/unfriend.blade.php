<x-button variant="primary-outline" wire:click="save" wire:confirm="Do you really want to do?"
          wire:loading.attr="disabled">
    {{ __('Unfriend') }}
</x-button>

