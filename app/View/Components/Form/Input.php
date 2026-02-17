<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $name;
    public $label;
    public $placeholder;
    public $value;

    public function __construct($type = 'text', $name, $label = null, $placeholder = null, $value = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.form.input');
    }
}
