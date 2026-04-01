window.addEventListener('open-modal', event => {
    const modal = document.getElementById(event.detail.id);
    if (modal) modal.showModal();
    Livewire.dispatch('modal-opened', { id: event.detail.id, data: event.detail.data ?? {} });
});

window.addEventListener('close-modal', event => {
    const modal = document.getElementById(event.detail.id);
    if (modal) modal.close();
    Livewire.dispatch('modal-closed', { id: event.detail.id });
});