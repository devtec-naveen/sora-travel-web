<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $mode;
    public $minDate;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $id,
        $name,
        $label = 'Select Date',
        $placeholder = 'Select date',
        $mode = 'date',
        $minDate = 'today',
        $value = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->mode = $mode;
        $this->minDate = $minDate;
        $this->value = $value;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.date-picker');
    }
}
