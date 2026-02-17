<?php

namespace App\Traits;

trait Toast
{
    public function Toast($type, $message)
    {
        $this->dispatch('notify',
            type: $type,
            message: $message
        );
    }

    public function SessionToast($type, $message)
    {
        session()->flash('toast', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
