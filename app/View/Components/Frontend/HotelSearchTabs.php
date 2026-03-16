<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HotelSearchTabs extends Component
{
    public bool $hidden;

    public function __construct(bool $hidden = true)
    {
        $this->hidden = $hidden;
    }

    public function render(): View|Closure|string
    {
        return view('components.frontend.hotel-search-tabs');
    }
}