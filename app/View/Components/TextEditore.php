<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextEditore extends Component
{
    public $id;
    public $model;
    public $value;

    public function __construct($id, $model, $value = '')
    {
        $this->id = $id;
        $this->model = $model;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.text-editore');
    }
}
