<div>
    <dialog id="{{ $modalId ?? 'confirm_delete_modal' }}" class="modal" wire:ignore.self>
        <div class="modal-box max-w-[400px] mx-auto flex flex-col p-0 rounded-2xl overflow-hidden bg-white shadow-md">
            <div class="px-5 pt-9 pb-5 flex flex-col gap-6">
                <div class="flex flex-col items-center gap-3.5">
                    <div
                        class="w-16 h-16 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 shadow-sm">
                        <i data-tabler="trash" data-stroke="2"></i>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <h3 id="confirm_title" class="text-2xl md:text-3xl font-bold text-slate-950 text-center">
                            {{ $title ?? 'Delete' }}
                        </h3>
                        <p id="confirm_message" class="text-sm md:text-base font-medium text-slate-500 text-center">
                            {{ $message ?? 'Are you sure you want to delete this item?' }}
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="{{ $confirmAction ?? 'deleteAddress' }}" id="confirm_delete_btn"
                    class="btn btn-primary w-full" wire:loading.attr="disabled"
                    wire:target="{{ $confirmAction ?? 'deleteAddress' }}">
                    <span wire:loading.remove wire:target="{{ $confirmAction ?? 'deleteAddress' }}">
                        Yes, Delete
                    </span>
                    <span wire:loading wire:target="{{ $confirmAction ?? 'deleteAddress' }}">
                        Processing...
                    </span>
                </button>
                <button type="button" wire:click="{{ $closeAction ?? 'closeModal' }}"
                    class="w-full text-sm font-semibold text-slate-500 hover:text-slate-700"
                    wire:loading.attr="disabled">
                    No
                </button>
            </div>
        </div>
    </dialog>
</div>
