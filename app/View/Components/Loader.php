<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Loader extends Component
{
    public $message;
    public $targets;

    public function __construct($message = 'Please wait...', $targets = null)
    {
        $this->message = $message;
        $this->targets = $targets;
    }

    public function render()
    {
        return view('components.loader');
    }
}