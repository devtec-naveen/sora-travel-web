<?php

namespace App\View\Components\frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $logo;
    public $header;
    public $headerText;
    /**
     * Create a new component instance.
     */
    public function __construct($id,$logo = true,$header = false,$headerText = null)
    {
        $this->id = $id;
        $this->logo = $logo;
        $this->header = $header;
        $this->headerText = $headerText;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.modal');
    }
}
