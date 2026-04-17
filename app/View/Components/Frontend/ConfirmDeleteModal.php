<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmDeleteModal extends Component
{
    public string $modalId;
    public string $title;
    public string $message;
    public string $confirmAction;
    public string $closeAction;

    public function __construct(
        $modalId = 'confirm_delete_modal',
        $title = 'Delete',
        $message = 'Are you sure you want to delete this item?',
        $confirmAction = 'deleteAddress',
        $closeAction = 'closeModal'
    ) {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->message = $message;
        $this->confirmAction = $confirmAction;
        $this->closeAction = $closeAction;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.confirm-delete-modal');
    }
}
