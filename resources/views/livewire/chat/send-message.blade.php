<div class="flex gap-2 items-center pr-2">
    <x-text-input wire:model="message" wire:click="startTyping" wire:blur="endTyping" name="message"
                  placeholder="Type Message..." class="w-full grow"/>
    <button class="w-[20px] h-[20px]" wire:click="save" wire:loading.attr="disabled">
        <svg id="fi_3682321" enable-background="new 0 0 404.644 404.644" width="20" height="20"
             viewBox="0 0 404.644 404.644" width="512" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path
                    d="m5.535 386.177c-3.325 15.279 8.406 21.747 19.291 16.867l367.885-188.638h.037c4.388-2.475 6.936-6.935 6.936-12.08 0-5.148-2.548-9.611-6.936-12.085h-.037l-367.885-188.641c-10.885-4.881-22.616 1.589-19.291 16.869.225 1.035 21.974 97.914 33.799 150.603l192.042 33.253-192.042 33.249c-11.825 52.686-33.575 149.567-33.799 150.603z"
                    fill="#096ad9"></path>
            </g>
        </svg>
    </button>
</div>
