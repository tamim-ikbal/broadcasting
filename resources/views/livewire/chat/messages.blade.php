<div class="flex flex-col gap-2" id="inbox-{{ $inbox->id }}">
    @foreach($messages as $message)
        @if($message['sender_id'] === auth()->id())
            <div
                class="max-w-[80%] md:max-w-[75%] lg:max-w-[70%] self-end p-3 rounded-[10px] border-[1px] border-gray-200 bg-blue-500 text-white">
                <p class="text-base">{{ $message['message'] ?? '' }}</p>
            </div>
        @else
            <div
                class="max-w-[80%] md:max-w-[75%] lg:max-w-[70%] p-3 rounded-[10px] border-[1px] border-gray-200 self-start">
                <p class="text-base">{{ $message['message'] ?? '' }}</p>
            </div>
        @endif
    @endforeach
</div>

@script
<script>
    Echo.private('messages.' + {{ $inbox->id }})
        .listen('MessageSent', (e) => {
            let message = document.createElement('div');
            let text = document.createElement('p');
            text.textContent = e.message.message;
            text.classList.add('text-base')

            if (e.message.sender_id === {{ auth()->id() }}) {
                message.classList.add('max-w-[80%]', 'md:max-w-[75%]', 'lg:max-w-[70%]', 'self-end', 'p-3', 'rounded-[10px]', 'border-[1px]', 'border-gray-200', 'bg-blue-500', 'text-white');
            } else {
                message.classList.add('max-w-[80%]', 'md:max-w-[75%]', 'lg:max-w-[70%]', 'p-3', 'rounded-[10px]', 'border-[1px]', 'border-gray-200', 'self-start')
            }
            message.appendChild(text)
            document.getElementById('inbox-{{ $inbox->id }}').appendChild(message);
            let chatBox = document.getElementById('chat-box')
            chatBox.scrollTo(0, chatBox.scrollHeight)
            console.log('Okay')
        })
        .listen('MessageTyping', function (e) {
            const typingEl = document.getElementById('chat-typing')
            if (e.typing) {
                typingEl.classList.remove('hidden');
            } else {
                typingEl.classList.add('hidden');
            }
            let chatBox = document.getElementById('chat-box')
            chatBox.scrollTo(0, chatBox.scrollHeight)
        })
</script>
@endscript
