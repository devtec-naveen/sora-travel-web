<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Autocomplete extends Component
{
    public $label;
    public $name;
    public $value;
    public $display;
    public $placeholder;
    public $type;
    public $icon;
    public $cityInputName;
    public $cityValue;
    public $latitude;
    public $longitude;

    public function __construct(
        $label         = '',
        $name          = '',
        $value         = '',
        $display       = '',
        $placeholder   = 'Search...',
        $type          = 'default',
        $icon          = '',
        $cityInputName = '',
        $cityValue     = '',
        $latitude      = '',
        $longitude     = ''
    ) {
        $this->label         = $label;
        $this->name          = $name;
        $this->value         = $value;
        $this->display       = $display;
        $this->placeholder   = $placeholder;
        $this->type          = $type;
        $this->icon          = $icon;
        $this->cityInputName = $cityInputName;
        $this->cityValue     = $cityValue;
        $this->latitude      = $latitude;
        $this->longitude     = $longitude;
    }

    public function render(): View|Closure|string
    {
        return view('components.frontend.autocomplete');
    }
}