<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Header extends Component
{
     public bool $isHome = false;

     public function mount()
     {
        $this->isHome = request()->routeIs('home');
     }

    /**
     * Open modal (by ID)
     */
    public function openModal($id)
    {
        $this->dispatch('open-modal', id: $id);
    }

    /**
     * Close modal (by ID)
     */
    public function closeModal($id)
    {
        $this->dispatch('close-modal', id: $id);
    }

    public function render()
    {
        return view('livewire.frontend.header');
    }
}