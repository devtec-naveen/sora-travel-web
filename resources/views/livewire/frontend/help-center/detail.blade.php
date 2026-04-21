<main class="bg-slate-50 min-h-[800px]">
    <div
        class="self-stretch min-h-[100px] md:min-h-[182px] py-5 bg-gradient-to-b from-primary-800 to-primary-900 flex flex-col justify-center items-center gap-2.5">
        <div class="text-white text-xl sm:text-3xl md:text-4xl font-bold leading-tight md:leading-[48px] text-center">
            Ticket Detail
        </div>
    </div>

    <div class="py-6 lg:py-12" wire:poll.15000ms>
        <div class="container">
            <div class="flex flex-col gap-6 max-w-3xl mx-auto">

                @if (session('success'))
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Back -->
                <a href="{{ route('front.helpCenter') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-primary-700 transition-colors w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to All Tickets
                </a>

                <!-- Ticket Header Card -->
                <div class="card p-5 flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex flex-col gap-1">
                            <div class="text-sm text-slate-500">#{{ $ticket->ticket_number }}</div>
                            <h1 class="text-xl font-semibold text-slate-950 leading-7">{{ $ticket->subject }}</h1>
                        </div>
                        <div class="tag {{ $ticket->statusTag() }}">
                            <span class="text-sm font-medium">{{ $ticket->statusLabel() }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                        <span>
                            <span class="font-medium text-slate-700">Category:</span>
                            {{ ucfirst($ticket->category) }}
                        </span>
                        <span>
                            <span class="font-medium text-slate-700">Priority:</span>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                        @if($ticket->order_id)
                            <span>
                                <span class="font-medium text-slate-700">Booking ID:</span>
                                {{ $ticket->order_id }}
                            </span>
                        @endif
                        <span>
                            <span class="font-medium text-slate-700">Opened:</span>
                            {{ $ticket->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    @if($ticket->isOpen())
                        <div class="flex justify-end">
                            <button
                                wire:click="closeTicket"
                                wire:confirm="Are you sure you want to close this ticket?"
                                class="btn btn-outline text-sm text-red-500 border-red-300 hover:bg-red-50">
                                Close Ticket
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Messages Thread -->
                <div class="flex flex-col gap-4">
                    @foreach($ticket->messages as $msg)
                        <div class="flex {{ $msg->isFromUser() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] flex flex-col gap-1.5 {{ $msg->isFromUser() ? 'items-end' : 'items-start' }}">

                                <!-- Sender + Time -->
                                <div class="text-xs text-slate-400 px-1">
                                    {{ $msg->isFromUser() ? 'You' : 'Support Team' }}
                                    &bull; {{ $msg->created_at->format('M d, Y h:i A') }}
                                </div>

                                <!-- Message bubble -->
                                <div class="p-3.5 rounded-xl text-sm leading-5
                                    {{ $msg->isFromUser()
                                        ? 'bg-primary-700 text-white rounded-br-sm'
                                        : 'bg-white border border-slate-100 text-slate-700 rounded-bl-sm shadow-sm' }}">
                                    {!! nl2br(e($msg->message)) !!}
                                </div>

                                <!-- Attachments -->
                                @if(!empty($msg->attachments))
                                    <div class="flex flex-col gap-1.5 w-full">
                                        @foreach($msg->attachments as $file)
                                            @php
                                                $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                                $isImage  = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                $fileUrl  = asset('uploads/' . $file['folder'] . '/' . $file['file']);
                                            @endphp

                                            @if($isImage)
                                                <a href="{{ $fileUrl }}" target="_blank" class="block">
                                                    <img
                                                        src="{{ $fileUrl }}"
                                                        alt="{{ $file['name'] }}"
                                                        class="max-w-[220px] rounded-lg border border-slate-200 shadow-sm object-cover cursor-pointer hover:opacity-90 transition-opacity" />
                                                </a>
                                            @else
                                                <a href="{{ $fileUrl }}"
                                                   target="_blank"
                                                   download="{{ $file['name'] }}"
                                                   class="flex items-center gap-2 px-3 py-2 rounded-lg border text-xs font-medium transition-colors
                                                       {{ $msg->isFromUser()
                                                           ? 'bg-primary-600 border-primary-500 text-white hover:bg-primary-500'
                                                           : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    <span class="truncate max-w-[160px]">{{ $file['name'] }}</span>
                                                    <svg class="w-3.5 h-3.5 shrink-0 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Box -->
                @if($ticket->isOpen())
                    <div class="card p-5">
                        <form wire:submit="sendReply" class="flex flex-col gap-3">
                            <label class="text-sm font-medium text-slate-700">Your Reply</label>
                            <textarea
                                wire:model="reply"
                                rows="4"
                                placeholder="Type your message here..."
                                class="w-full p-2.5 bg-white rounded-md border border-slate-200 shadow-sm text-sm font-normal text-slate-950 placeholder:text-slate-400 focus:outline-none focus:ring-0 focus:border-primary-400 resize-none transition-colors
                                    @error('reply') border-red-400 @enderror"></textarea>
                            @error('reply')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="sendReply">
                                    <span wire:loading.remove wire:target="sendReply">Send Reply</span>
                                    <span wire:loading wire:target="sendReply">Sending...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card p-4 text-center text-slate-400 text-sm border border-slate-100">
                        This ticket is <span class="font-medium capitalize">{{ $ticket->status }}</span>. You cannot reply to a closed ticket.
                    </div>
                @endif

            </div>
        </div>
    </div>
</main>
