<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImagePreview extends Component
{
    public $path;
    public $folder;
    public $width;

    public function __construct($path = null, $folder = 'uploads', $width = null)
    {
        $this->path = $path;
        $this->folder = $folder;
        $this->width = $width;
    }

    public function render()
    {
        return view('components.image-preview');
    }
}