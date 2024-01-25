<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Components\ImageGallery\ImageGalleryControl;

class ScholarshipPresenter extends BasePresenter
{
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->context);
    }
}
