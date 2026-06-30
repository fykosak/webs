<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Models\Images\ImageService;

class DefaultPresenter extends BasePresenter
{
    private readonly ImageService $imageService;

    public function inject(ImageService $imageService): void
    {
        $this->imageService = $imageService;
    }

    public function renderDefault(): void
    {
        $this->template->imageService = $this->imageService;
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }
}
