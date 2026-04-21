<main class="bg-slate-50 min-h-[800px]">
    <div
        class="self-stretch min-h-[100px] md:min-h-[182px] py-5 bg-gradient-to-b from-primary-800 to-primary-900 flex flex-col justify-center items-center gap-2.5">
        <div class="text-white text-xl sm:text-3xl md:text-4xl font-bold leading-tight md:leading-[48px] text-center">
            Help Center
        </div>
    </div>

    <div class="py-6 lg:py-12" wire:poll.15000ms>
        <div class="container">
            <div class="flex flex-col gap-6">

                @if (session('success'))
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-2xl font-semibold text-slate-950 leading-9">All Tickets</h1>
                    @auth
                        <button
                            wire:click="$dispatch('open-modal', {id: 'raise_ticket_modal'})"
                            class="btn btn-primary">
                            Raise A Ticket
                        </button>
                    @else
                        <button
                            wire:click="$dispatch('open-modal', {id: 'login_modal'})"
                            class="btn btn-primary">
                            Raise A Ticket
                        </button>
                    @endauth
                </div>

                <!-- Filters -->
                <div class="flex gap-2 flex-wrap">
                    @foreach(['', 'open', 'in_progress', 'resolved', 'closed'] as $s)
                        <button
                            wire:click="$set('filterStatus', '{{ $s }}')"
                            class="px-3 py-1.5 text-sm rounded-full border transition-colors
                                {{ $filterStatus === $s
                                    ? 'bg-primary-700 text-white border-primary-700'
                                    : 'bg-white text-slate-600 border-slate-200 hover:border-primary-400' }}">
                            {{ $s === '' ? 'All' : ucwords(str_replace('_', ' ', $s)) }}
                        </button>
                    @endforeach
                </div>

                <!-- Tickets List -->
                <div class="flex flex-col gap-4">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('front.helpCenter.detail', encodeId($ticket->id)) }}" class="card p-4 block hover:shadow-md transition-shadow">
                            <div class="flex flex-col gap-2.5">
                                <!-- Ticket ID and Status -->
                                <div class="flex items-center gap-2.5">
                                    <div class="flex-1 text-sm font-normal text-slate-500 leading-5">
                                        #{{ $ticket->ticket_number }}
                                    </div>
                                    <div class="tag {{ $ticket->statusTag() }}">
                                        <span class="text-sm font-medium leading-5">{{ $ticket->statusLabel() }}</span>
                                    </div>
                                </div>

                                <!-- Ticket Details -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex gap-1 sm:flex-row flex-col md:items-center">
                                        <div class="flex-1 text-lg font-semibold text-slate-950 leading-7">
                                            {{ $ticket->subject }}
                                        </div>
                                        <div class="text-base font-normal text-slate-500 leading-6">
                                            {{ $ticket->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    @if($ticket->order_id)
                                        <div class="text-sm font-normal text-slate-950 leading-5">
                                            Booking ID: {{ $ticket->order_id }}
                                        </div>
                                    @endif
                                    <div class="text-sm font-normal text-slate-500 leading-5 line-clamp-2">
                                        {{ $ticket->description }}
                                    </div>
                                </div>

                                <!-- Last Reply -->
                                @if($ticket->latestMessage && $ticket->latestMessage->sender_type === 'admin')
                                    <div class="p-2.5 bg-slate-50 rounded-md flex flex-col gap-1.5 border border-slate-100">
                                        <div class="text-base font-semibold text-slate-950 leading-6">Response:</div>
                                        <div class="text-sm font-normal text-slate-500 leading-5 line-clamp-2">
                                            {{ $ticket->latestMessage->message }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="card p-10 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-base font-medium">No tickets found</p>
                            <p class="text-sm mt-1">Raise a ticket if you need help with anything.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</main>
