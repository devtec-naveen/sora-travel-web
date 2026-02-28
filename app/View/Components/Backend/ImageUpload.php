<?php

namespace App\View\Components\Backend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageUpload extends Component
{
    public $previewId;
    public $maxWidth;
    public $maxHeight;
    public $currentImage;
    public $folderPath;

    public function __construct($previewId = 'imageUploadPreview', $maxWidth = 1000, $maxHeight = 500,$currentImage = null,$folderPath = null)
    {
        $this->previewId = $previewId;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->currentImage = $currentImage;
        $this->folderPath = $folderPath;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.image-upload');
    }
}
