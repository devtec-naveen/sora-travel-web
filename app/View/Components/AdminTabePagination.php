<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminTabePagination extends Component
{
    public $paginator;

    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }

    public function render()
    {
        return view('components.admin-tabe-pagination');
    }
}