<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Travelers extends Component
{
    public $adults;
    public $children;
    public $infants;
    public $cabinClass;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $adults = 1,
        $children = 0,
        $infants = 0,
        $cabinClass = 'Economy'
    ) {
        $this->adults = $adults ?? 1;
        $this->children = $children ?? 0;
        $this->infants = $infants ?? 0;
        $this->cabinClass = $cabinClass ?? 'Economy';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.travelers');
    }
}
