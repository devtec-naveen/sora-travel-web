<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableLoader extends Component
{
    public $rows;
    public $columns;

    public function __construct($rows = 10, $columns = 6)
    {
        $this->rows = $rows;
        $this->columns = $columns;
    }

    public function render(): View|Closure|string
    {
        return view('components.table-loader');
    }
}