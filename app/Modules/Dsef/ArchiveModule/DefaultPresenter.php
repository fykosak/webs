<?php

declare(strict_types=1);

namespace App\Modules\Dsef\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }
}
